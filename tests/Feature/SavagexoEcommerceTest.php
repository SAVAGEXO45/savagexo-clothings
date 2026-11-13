<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Services\PaymentService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavagexoEcommerceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_homepage_and_shop_pages_render_with_seeded_fashion_and_sneaker_catalog(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SAVAGEXO');
        $response->assertSee('SAVAGEXO@GMAIL.COM');
        $response->assertSee('New Sneaker Arrivals');
        $response->assertSee('Nike & Air Jordan Collection', false);
        $response->assertSee('Adidas Originals Collection');
        $response->assertSee('Obsidian Double-Breasted Virgin Wool Overcoat');
        $response->assertSee('The Sovereign 3-Piece Noir Cashmere Agbada Ensemble');

        $shopResponse = $this->get('/shop?category=traditional-wear');
        $shopResponse->assertStatus(200);
        $shopResponse->assertSee('Traditional Wear');
        $shopResponse->assertSee('The Sovereign 3-Piece Noir Cashmere Agbada Ensemble');
    }

    public function test_contact_email_is_updated_to_savagexo_gmail_everywhere(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('SAVAGEXO@GMAIL.COM')->assertDontSee('concierge@savagexo.com');
        $this->get('/contact')->assertStatus(200)->assertSee('SAVAGEXO@GMAIL.COM');
        $this->get('/about')->assertStatus(200)->assertSee('SAVAGEXO@GMAIL.COM');

        // Submit customer support contact form
        $contactPost = $this->post('/contact', [
            'name' => 'Tunde Bakare',
            'email' => 'tunde@example.com',
            'phone' => '+234 803 123 4567',
            'subject' => 'Sneaker Sizing Inquiry',
            'message' => 'Do you have the Air Jordan 1 High in EU 44 ready for Lagos delivery?',
        ]);
        $contactPost->assertRedirect();
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'tunde@example.com',
            'subject' => 'Sneaker Sizing Inquiry',
        ]);
    }

    public function test_sneaker_collection_page_and_brand_and_style_filters_work(): void
    {
        // Dedicated /sneakers page (paginated)
        $sneakersResp = $this->get('/sneakers');
        $sneakersResp->assertStatus(200);
        $sneakersResp->assertSee('SAVAGEXO SNEAKER VAULT');
        $sneakersResp->assertSee('On Running Cloudmonster Hyper Helion Superfoam');
        $sneakersResp->assertSee('Salomon XT-6 Expanse GORE-TEX Trail Technical');

        // Filter by brand = nike
        $nikeResp = $this->get('/sneakers?brand=nike');
        $nikeResp->assertStatus(200);
        $nikeResp->assertSee('nike-air-force-1-07');
        $nikeResp->assertSee('Nike Dunk Low Retro Black White Panda');

        // Filter by style = Running
        $runningResp = $this->get('/sneakers?style=Running');
        $runningResp->assertStatus(200);
        $runningResp->assertSee('Nike Air Zoom Pegasus 41 ReactX Road Runner');
        $runningResp->assertSee('Adidas Ultraboost Light Primeknit Performance');

        // Filter by shoe_size = 42 and sort = best_selling
        $sizeSortResp = $this->get('/sneakers?shoe_size=42&sort=best_selling');
        $sizeSortResp->assertStatus(200);
    }

    public function test_all_clothing_and_sneaker_products_have_multiple_gallery_images(): void
    {
        $products = Product::with('images')->get();
        $this->assertGreaterThanOrEqual(34, $products->count());

        foreach ($products as $product) {
            $this->assertGreaterThanOrEqual(
                5,
                $product->images->count(),
                "Product {$product->slug} should have at least 5 gallery images."
            );
        }

        // Verify product detail gallery displays view angles and zoom controls
        $sneaker = Product::where('slug', 'nike-air-force-1-07')->firstOrFail();
        $detailResponse = $this->get('/product/' . $sneaker->slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('SXO-NK-AF1');
        $detailResponse->assertSee('Lateral Side Profile');
        $detailResponse->assertSee('Hover / Click Zoom');
    }

    public function test_product_details_page_and_live_search_api_work(): void
    {
        $product = Product::where('slug', 'sovereign-3-piece-noir-cashmere-agbada-ensemble')->firstOrFail();

        $detailResponse = $this->get('/product/' . $product->slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($product->name);
        $detailResponse->assertSee('₦375,000');

        $apiResponse = $this->getJson('/api/products/search?q=Nike');
        $apiResponse->assertStatus(200)
            ->assertJsonFragment(['slug' => 'nike-air-force-1-07']);
    }

    public function test_shopping_bag_coupon_checkout_and_paystack_verification_flow(): void
    {
        $customer = User::where('email', 'amara.okafor@gmail.com')->firstOrFail();
        $product = Product::where('slug', '600gsm-heavyweight-boxy-cropped-hoodie-washed-noir')->firstOrFail();

        $this->actingAs($customer);

        // 1. Add 2 units of ₦78,000 hoodie (Subtotal = ₦156,000)
        $addResponse = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
            'size' => 'L',
            'color' => 'Washed Noir',
        ]);
        $addResponse->assertStatus(200)->assertJson(['success' => true, 'cart_count' => 2]);

        // 2. Apply SAVAGE15 coupon (15% off orders over ₦80,000 => ₦23,400 discount)
        $couponResponse = $this->post('/cart/coupon', ['coupon_code' => 'SAVAGE15']);
        $couponResponse->assertRedirect();

        // 3. Submit Checkout with Paystack
        $checkoutResponse = $this->post('/checkout', [
            'customer_name' => 'Amara Okafor',
            'customer_email' => 'amara.okafor@gmail.com',
            'customer_phone' => '+234 803 451 9920',
            'shipping_address' => '14B Adeola Odeku Street, Victoria Island',
            'shipping_city' => 'Lagos',
            'shipping_state' => 'Lagos State',
            'shipping_postal_code' => '106104',
            'shipping_country' => 'Nigeria',
            'shipping_method' => 'standard',
            'payment_method' => 'paystack',
        ]);

        $payment = Payment::latest('id')->firstOrFail();
        $checkoutResponse->assertRedirect(route('payment.gateway', $payment->transaction_reference));

        // Verify order is pending before backend payment verification
        $this->assertEquals('pending', $payment->order->payment_status);

        // 4. Verify Payment on Backend with Valid HMAC Signature
        $paymentService = app(PaymentService::class);
        $validSignature = $paymentService->generateSandboxSignature($payment);

        $verifyResponse = $this->post(route('payment.verify', $payment->transaction_reference), [
            'signature' => $validSignature,
            'outcome' => 'success',
            'channel' => 'card',
        ]);

        $verifyResponse->assertRedirect(route('checkout.confirmation', $payment->order->order_number));

        $this->assertEquals('paid', $payment->order->fresh()->payment_status);
        $this->assertEquals('confirmed', $payment->order->fresh()->order_status);
    }

    public function test_customer_dashboard_wishlist_and_address_book_work(): void
    {
        $customer = User::where('email', 'amara.okafor@gmail.com')->firstOrFail();
        $product = Product::firstOrFail();

        $this->actingAs($customer);

        $this->get('/account')->assertStatus(200)->assertSee('Amara Okafor');
        $this->get('/account/orders')->assertStatus(200);
        $this->get('/account/wishlist')->assertStatus(200);
        $this->get('/account/addresses')->assertStatus(200);
        $this->get('/account/profile')->assertStatus(200);

        $toggleResponse = $this->postJson('/wishlist/toggle/' . $product->id);
        $toggleResponse->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_admin_can_manage_brands_sneakers_and_product_gallery_images(): void
    {
        $customer = User::where('email', 'amara.okafor@gmail.com')->firstOrFail();
        $admin = User::where('email', 'admin@savagexo.com')->firstOrFail();

        // Customer must receive 403 Forbidden on admin routes
        $this->actingAs($customer)->get('/admin')->assertStatus(403);

        // Admin can access all admin modules including Sneaker Brands
        $this->actingAs($admin);
        $this->get('/admin')->assertStatus(200)->assertSee('Atelier Executive Command Center');
        $this->get('/admin/brands')->assertStatus(200)->assertSee('Nike')->assertSee('Adidas');
        $this->get('/admin/products')->assertStatus(200);
        $this->get('/admin/products/create')->assertStatus(200);
        $this->get('/admin/categories')->assertStatus(200);
        $this->get('/admin/orders')->assertStatus(200);
        $this->get('/admin/customers')->assertStatus(200);
        $this->get('/admin/coupons')->assertStatus(200);
        $this->get('/admin/reviews')->assertStatus(200);
        $this->get('/admin/banners')->assertStatus(200)->assertSee('SAVAGEXO@GMAIL.COM');

        // Admin can add a new sneaker brand
        $brandResp = $this->post('/admin/brands', [
            'name' => 'Balenciaga',
            'type' => 'sneaker',
            'description' => 'Architectural luxury runners and oversized silhouettes.',
            'is_featured' => 1,
            'sort_order' => 17,
        ]);
        $brandResp->assertRedirect();
        $brand = Brand::where('slug', 'balenciaga')->firstOrFail();

        // Admin can create a new sneaker product with multiple gallery images
        $cat = Category::where('slug', 'sneakers')->firstOrFail();
        $createResp = $this->post('/admin/products', [
            'name' => 'Balenciaga Runner Mesh & Nylon Grey',
            'category_id' => $cat->id,
            'brand_id' => $brand->id,
            'product_type' => 'sneaker',
            'sneaker_style' => 'Luxury',
            'sku' => 'SXO-SNK-999',
            'short_description' => 'Hand-distressed multi-layered technical runner.',
            'description' => 'Crafted with layered mesh, nylon, and polyurethane panels.',
            'price' => 650000,
            'discount_price' => 595000,
            'stock_quantity' => 10,
            'gender' => 'unisex',
            'sizes_csv' => '40, 41, 42, 43, 44, 45',
            'colors_csv' => 'Metallic Grey:#8A8D91, Obsidian Black:#111111',
            'featured_image_url' => '/images/sneakers/asics-gel-kayano-14-lateral.jpg',
            'gallery_urls_text' => "/images/sneakers/asics-gel-kayano-14-medial.jpg|Medial Profile|Metallic Grey\n/images/sneakers/asics-gel-kayano-14-heel.jpg|Heel View|Obsidian Black",
            'is_active' => 1,
            'is_featured' => 1,
            'is_new_arrival' => 1,
        ]);
        $createResp->assertRedirect(route('admin.products'));

        $createdSneaker = Product::where('sku', 'SXO-SNK-999')->firstOrFail();
        $this->assertEquals(3, $createdSneaker->images()->count());

        // Admin can edit and delete an individual product image
        $extraImage = $createdSneaker->images()->where('is_primary', false)->firstOrFail();
        $this->patch(route('admin.products.images.update', $extraImage), [
            'view_angle' => 'Updated Outsole Macro View',
            'color_name' => 'Metallic Grey',
        ])->assertRedirect();
        $this->assertEquals('Updated Outsole Macro View', $extraImage->fresh()->view_angle);

        $this->delete(route('admin.products.images.delete', $extraImage))->assertRedirect();
        $this->assertDatabaseMissing('product_images', ['id' => $extraImage->id]);

        // Admin can update order status
        $order = Order::firstOrFail();
        $this->patch(route('admin.orders.status', $order), [
            'order_status' => 'delivered',
            'payment_status' => 'paid',
        ])->assertRedirect();
        $this->assertEquals('delivered', $order->fresh()->order_status);
    }
}
