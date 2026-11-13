<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $paidOrdersCount = Order::where('payment_status', 'paid')->count();
        $pendingOrdersCount = Order::whereIn('order_status', ['pending', 'confirmed', 'processing'])->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalSneakers = Product::where('product_type', 'sneaker')->count();
        $totalBrands = Brand::count();
        $lowStockProducts = Product::with('category')->where('stock_quantity', '<=', 5)->orderBy('stock_quantity')->take(6)->get();

        $recentOrders = Order::with(['user', 'items'])->latest()->take(8)->get();
        $topSellingProducts = Product::with('category')->orderByDesc('sales_count')->take(5)->get();
        $categoryBreakdown = Category::withCount('products')->orderByDesc('products_count')->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'paidOrdersCount',
            'pendingOrdersCount',
            'totalCustomers',
            'totalProducts',
            'totalSneakers',
            'totalBrands',
            'lowStockProducts',
            'recentOrders',
            'topSellingProducts',
            'categoryBreakdown'
        ));
    }

    // =========================================================================
    // PRODUCTS & INVENTORY MANAGEMENT
    // =========================================================================
    public function products(Request $request): View
    {
        $query = Product::with(['category', 'brand', 'variants', 'images']);

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($catId = $request->query('category_id')) {
            $query->where('category_id', $catId);
        }

        if ($brandId = $request->query('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        if ($type = $request->query('product_type')) {
            $query->where('product_type', $type);
        }

        $products = $query->latest('id')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function createProduct(): View
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.form', [
            'product' => null,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'brand_name' => ['nullable', 'string', 'max:80'],
            'product_type' => ['nullable', 'in:clothing,sneaker,accessory'],
            'sneaker_style' => ['nullable', 'string', 'max:60'],
            'sku' => ['nullable', 'string', 'max:60', 'unique:products,sku'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'gender' => ['required', 'in:men,women,unisex'],
            'material' => ['nullable', 'string', 'max:180'],
            'care_instructions' => ['nullable', 'string', 'max:500'],
            'featured_image_url' => ['nullable', 'string', 'max:500'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_files.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_urls_text' => ['nullable', 'string'],
            'sizes_csv' => ['nullable', 'string', 'max:255'],
            'colors_csv' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-' . strtolower(Str::random(4));
        }

        $sku = ! empty($validated['sku'])
            ? strtoupper($validated['sku'])
            : 'SXO-' . strtoupper(Str::random(6));

        $brandModel = ! empty($validated['brand_id']) ? Brand::find($validated['brand_id']) : null;
        $brandName = $brandModel?->name ?? (($validated['brand_name'] ?? null) ?: 'SAVAGEXO Atelier');

        $featuredImage = ($validated['featured_image_url'] ?? null) ?: '/images/products/agbada-front.jpg';
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $filename = time() . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
            $featuredImage = '/images/products/' . $filename;
        }

        DB::transaction(function () use ($validated, $request, $slug, $sku, $featuredImage, $brandModel, $brandName) {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'brand_id' => $brandModel?->id,
                'brand_name' => $brandName,
                'name' => $validated['name'],
                'slug' => $slug,
                'sku' => $sku,
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'discount_price' => ! empty($validated['discount_price']) ? $validated['discount_price'] : null,
                'stock_quantity' => $validated['stock_quantity'],
                'gender' => $validated['gender'],
                'sneaker_style' => $validated['sneaker_style'] ?? null,
                'product_type' => $validated['product_type'] ?? 'clothing',
                'material' => $validated['material'] ?? '100% Luxury Heavyweight Cotton & Silk Blend',
                'care_instructions' => $validated['care_instructions'] ?? 'Dry clean only or cold gentle hand wash.',
                'featured_image' => $featuredImage,
                'is_featured' => $request->boolean('is_featured'),
                'is_new_arrival' => $request->boolean('is_new_arrival', true),
                'is_best_seller' => $request->boolean('is_best_seller'),
                'is_trending' => $request->boolean('is_trending'),
                'is_active' => $request->boolean('is_active', true),
                'meta_title' => $validated['name'] . ' | SAVAGEXO CLOTHINGS',
                'meta_description' => $validated['short_description'] ?? Str::limit($validated['description'], 155),
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $featuredImage,
                'alt_text' => $product->name . ' — Front View',
                'view_angle' => 'Front View',
                'is_primary' => true,
                'sort_order' => 1,
            ]);

            $sort = 2;
            if (! empty($validated['gallery_urls_text'])) {
                $lines = array_filter(array_map('trim', explode("\n", $validated['gallery_urls_text'])));
                foreach ($lines as $line) {
                    $parts = array_map('trim', explode('|', $line));
                    $imgUrl = $parts[0] ?? '';
                    if ($imgUrl === '') {
                        continue;
                    }
                    $angle = $parts[1] ?? ('Gallery Angle ' . $sort);
                    $color = $parts[2] ?? null;

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imgUrl,
                        'alt_text' => $product->name . ' — ' . $angle,
                        'view_angle' => $angle,
                        'color_name' => $color,
                        'is_primary' => false,
                        'sort_order' => $sort++,
                    ]);
                }
            }

            if ($request->hasFile('gallery_files')) {
                foreach ($request->file('gallery_files') as $gFile) {
                    if (! $gFile) {
                        continue;
                    }
                    $gName = time() . '-' . $sort . '-' . Str::slug(pathinfo($gFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $gFile->getClientOriginalExtension();
                    $gFile->move(public_path('images/products'), $gName);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => '/images/products/' . $gName,
                        'alt_text' => $product->name . ' — View ' . $sort,
                        'view_angle' => 'Angle View ' . $sort,
                        'is_primary' => false,
                        'sort_order' => $sort++,
                    ]);
                }
            }

            $this->syncProductVariants($product, $validated['sizes_csv'] ?? 'S, M, L, XL', $validated['colors_csv'] ?? 'Obsidian Black:#111111, Ivory Cream:#F5F2EB');
        });

        return redirect()->route('admin.products')->with('success', 'New product created and published.');
    }

    public function editProduct(Product $product): View
    {
        $product->load(['images', 'allVariants', 'brand']);
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories', 'brands'));
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'brand_name' => ['nullable', 'string', 'max:80'],
            'product_type' => ['nullable', 'in:clothing,sneaker,accessory'],
            'sneaker_style' => ['nullable', 'string', 'max:60'],
            'sku' => ['required', 'string', 'max:60', Rule::unique('products', 'sku')->ignore($product->id)],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'gender' => ['required', 'in:men,women,unisex'],
            'material' => ['nullable', 'string', 'max:180'],
            'care_instructions' => ['nullable', 'string', 'max:500'],
            'featured_image_url' => ['nullable', 'string', 'max:500'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_files.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_urls_text' => ['nullable', 'string'],
            'sizes_csv' => ['nullable', 'string', 'max:255'],
            'colors_csv' => ['nullable', 'string', 'max:255'],
        ]);

        $brandModel = ! empty($validated['brand_id']) ? Brand::find($validated['brand_id']) : null;
        $brandName = $brandModel?->name ?? (($validated['brand_name'] ?? null) ?: $product->brand_name);

        $featuredImage = ($validated['featured_image_url'] ?? null) ?: $product->featured_image;
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $filename = time() . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
            $featuredImage = '/images/products/' . $filename;
        }

        DB::transaction(function () use ($product, $validated, $request, $featuredImage, $brandModel, $brandName) {
            $product->update([
                'category_id' => $validated['category_id'],
                'brand_id' => $brandModel?->id ?? $product->brand_id,
                'brand_name' => $brandName,
                'name' => $validated['name'],
                'sku' => strtoupper($validated['sku']),
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'discount_price' => ! empty($validated['discount_price']) ? $validated['discount_price'] : null,
                'stock_quantity' => $validated['stock_quantity'],
                'gender' => $validated['gender'],
                'sneaker_style' => $validated['sneaker_style'] ?? $product->sneaker_style,
                'product_type' => $validated['product_type'] ?? $product->product_type,
                'material' => $validated['material'] ?? $product->material,
                'care_instructions' => $validated['care_instructions'] ?? $product->care_instructions,
                'featured_image' => $featuredImage,
                'is_featured' => $request->boolean('is_featured'),
                'is_new_arrival' => $request->boolean('is_new_arrival'),
                'is_best_seller' => $request->boolean('is_best_seller'),
                'is_trending' => $request->boolean('is_trending'),
                'is_active' => $request->boolean('is_active'),
            ]);

            $sort = (int) $product->images()->max('sort_order') + 1;

            if (! empty($validated['gallery_urls_text'])) {
                $lines = array_filter(array_map('trim', explode("\n", $validated['gallery_urls_text'])));
                foreach ($lines as $line) {
                    $parts = array_map('trim', explode('|', $line));
                    $imgUrl = $parts[0] ?? '';
                    if ($imgUrl === '') {
                        continue;
                    }
                    $angle = $parts[1] ?? ('Gallery Angle ' . $sort);
                    $color = $parts[2] ?? null;

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imgUrl,
                        'alt_text' => $product->name . ' — ' . $angle,
                        'view_angle' => $angle,
                        'color_name' => $color,
                        'is_primary' => false,
                        'sort_order' => $sort++,
                    ]);
                }
            }

            if ($request->hasFile('gallery_files')) {
                foreach ($request->file('gallery_files') as $gFile) {
                    if (! $gFile) {
                        continue;
                    }
                    $gName = time() . '-' . $sort . '-' . Str::slug(pathinfo($gFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $gFile->getClientOriginalExtension();
                    $gFile->move(public_path('images/products'), $gName);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => '/images/products/' . $gName,
                        'alt_text' => $product->name . ' — View ' . $sort,
                        'view_angle' => 'Angle View ' . $sort,
                        'is_primary' => false,
                        'sort_order' => $sort++,
                    ]);
                }
            }

            if (! empty($validated['sizes_csv']) && ! empty($validated['colors_csv'])) {
                $product->allVariants()->delete();
                $this->syncProductVariants($product, $validated['sizes_csv'], $validated['colors_csv']);
            }
        });

        return redirect()->route('admin.products.edit', $product)->with('success', "Product '{$product->name}' updated.");
    }

    public function updateProductImage(Request $request, ProductImage $image): RedirectResponse
    {
        $validated = $request->validate([
            'view_angle' => ['required', 'string', 'max:60'],
            'color_name' => ['nullable', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('is_primary')) {
            ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
            $image->product->update(['featured_image' => $image->image_path]);
        }

        $image->update([
            'view_angle' => $validated['view_angle'],
            'color_name' => $validated['color_name'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? $image->sort_order),
            'is_primary' => $request->boolean('is_primary', $image->is_primary),
        ]);

        return back()->with('success', 'Gallery image metadata updated.');
    }

    public function deleteProductImage(ProductImage $image): RedirectResponse
    {
        $image->delete();
        return back()->with('success', 'Product gallery image removed.');
    }

    public function deleteProduct(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products')->with('success', "Product '{$name}' deleted.");
    }

    private function syncProductVariants(Product $product, string $sizesCsv, string $colorsCsv): void
    {
        $sizes = array_filter(array_map('trim', explode(',', $sizesCsv)));
        $colorEntries = array_filter(array_map('trim', explode(',', $colorsCsv)));

        if (empty($sizes)) {
            $sizes = ['M', 'L'];
        }
        if (empty($colorEntries)) {
            $colorEntries = ['Obsidian Black:#111111'];
        }

        foreach ($sizes as $size) {
            foreach ($colorEntries as $colorEntry) {
                $parts = explode(':', $colorEntry);
                $colorName = trim($parts[0]);
                $colorHex = isset($parts[1]) ? trim($parts[1]) : '#111111';

                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'color' => $colorName,
                    'color_hex' => $colorHex,
                    'sku_variant' => $product->sku . '-' . strtoupper(Str::slug($size)) . '-' . strtoupper(substr(Str::slug($colorName), 0, 3)),
                    'stock_quantity' => max(3, (int) ceil($product->stock_quantity / max(1, count($sizes)))),
                    'price_adjustment' => 0,
                    'is_active' => true,
                ]);
            }
        }
    }

    // =========================================================================
    // SNEAKER & FASHION BRANDS MANAGEMENT
    // =========================================================================
    public function brands(): View
    {
        $brands = Brand::withCount('products')->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function storeBrand(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'type' => ['required', 'in:sneaker,fashion,luxury'],
            'origin_country' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $slug = Str::slug($validated['name']);
        if (Brand::where('slug', $slug)->exists()) {
            $slug .= '-' . strtolower(Str::random(3));
        }

        Brand::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'],
            'origin_country' => $validated['origin_country'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_featured' => $request->boolean('is_featured', true),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => (int) ($validated['sort_order'] ?? 10),
        ]);

        return back()->with('success', "Brand '{$validated['name']}' added to the catalog.");
    }

    public function deleteBrand(Brand $brand): RedirectResponse
    {
        $brand->delete();
        return back()->with('success', 'Brand removed.');
    }

    // =========================================================================
    // CATEGORIES MANAGEMENT
    // =========================================================================
    public function categories(): View
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'gender_group' => ['required', 'in:men,women,unisex,accessories'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $slug = Str::slug($validated['name']);
        if (Category::where('slug', $slug)->exists()) {
            $slug .= '-' . strtolower(Str::random(3));
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'gender_group' => $validated['gender_group'],
            'description' => $validated['description'] ?? null,
            'image' => ($validated['image'] ?? null) ?: '/images/products/agbada-front.jpg',
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        return back()->with('success', 'Category created.');
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'gender_group' => ['required', 'in:men,women,unisex,accessories'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'gender_group' => $validated['gender_group'],
            'description' => $validated['description'] ?? $category->description,
            'image' => ($validated['image'] ?? null) ?: $category->image,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) ($validated['sort_order'] ?? $category->sort_order),
        ]);

        return back()->with('success', 'Category updated.');
    }

    public function deleteCategory(Category $category): RedirectResponse
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete a category that still has products assigned.');
        }

        $category->delete();
        return back()->with('success', 'Category removed.');
    }

    // =========================================================================
    // ORDERS MANAGEMENT
    // =========================================================================
    public function orders(Request $request): View
    {
        $query = Order::with(['user', 'items', 'latestPayment']);

        if ($status = $request->query('status')) {
            $query->where('order_status', $status);
        }

        if ($paymentStatus = $request->query('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order): View
    {
        $order->load(['items.product', 'payments', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => ['required', 'in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled'],
            'payment_status' => ['required', 'in:pending,paid,failed,refunded'],
        ]);

        $updates = [
            'order_status' => $validated['order_status'],
            'payment_status' => $validated['payment_status'],
        ];

        if ($validated['payment_status'] === 'paid' && ! $order->paid_at) {
            $updates['paid_at'] = now();
        }
        if ($validated['order_status'] === 'shipped' && ! $order->shipped_at) {
            $updates['shipped_at'] = now();
        }
        if ($validated['order_status'] === 'delivered' && ! $order->delivered_at) {
            $updates['delivered_at'] = now();
        }

        $order->update($updates);

        return back()->with('success', "Order {$order->order_number} status updated.");
    }

    // =========================================================================
    // CUSTOMERS MANAGEMENT
    // =========================================================================
    public function customers(): View
    {
        $customers = User::withCount('orders')
            ->withSum(['orders as paid_spend' => fn ($q) => $q->where('payment_status', 'paid')], 'total_amount')
            ->latest()
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function toggleCustomerStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own administrator account.');
        }

        $user->update(['is_active' => ! $user->is_active]);
        $statusLabel = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Customer account {$user->name} has been {$statusLabel}.");
    }

    // =========================================================================
    // DISCOUNT COUPONS MANAGEMENT
    // =========================================================================
    public function coupons(): View
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function storeCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'description' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:1'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
        ]);

        Coupon::create([
            'code' => strtoupper(trim($validated['code'])),
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_order_amount' => $validated['min_order_amount'] ?? 0,
            'max_discount_amount' => $validated['max_discount_amount'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Privilege coupon created.');
    }

    public function toggleCoupon(Coupon $coupon): RedirectResponse
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);
        return back()->with('success', "Coupon {$coupon->code} status updated.");
    }

    public function deleteCoupon(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }

    // =========================================================================
    // REVIEWS MANAGEMENT
    // =========================================================================
    public function reviews(): View
    {
        $reviews = Review::with(['product', 'user'])->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleReviewApproval(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => ! $review->is_approved]);
        return back()->with('success', 'Review visibility updated.');
    }

    public function deleteReview(Review $review): RedirectResponse
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    // =========================================================================
    // HOMEPAGE BANNERS & SHIPPING SETTINGS MANAGEMENT
    // =========================================================================
    public function banners(): View
    {
        $banners = Banner::orderBy('position')->orderBy('sort_order')->get();
        $shippingStandard = StoreSetting::getValue('shipping_fee_standard', 4500);
        $shippingExpress = StoreSetting::getValue('shipping_fee_express', 9500);
        $shippingSameDay = StoreSetting::getValue('shipping_fee_lagos_same_day', 15000);
        $freeShippingThreshold = StoreSetting::getValue('free_shipping_threshold', 250000);
        $announcementText = StoreSetting::getValue('announcement_text', 'LAGOS • LONDON • MILAN — COMPLIMENTARY EXPRESS DELIVERY ON ORDERS OVER ₦250,000 | CONTACT: SAVAGEXO@GMAIL.COM');
        $contactEmail = StoreSetting::getValue('contact_email', 'SAVAGEXO@GMAIL.COM');
        $codEnabled = StoreSetting::getValue('cod_enabled', '1');

        return view('admin.banners.index', compact(
            'banners',
            'shippingStandard',
            'shippingExpress',
            'shippingSameDay',
            'freeShippingThreshold',
            'announcementText',
            'contactEmail',
            'codEnabled'
        ));
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:600'],
            'image_path' => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cta_text' => ['required', 'string', 'max:60'],
            'cta_link' => ['required', 'string', 'max:255'],
            'position' => ['required', 'in:hero,sneaker_hero,promo_primary,promo_secondary'],
            'badge_text' => ['nullable', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $imagePath = ($validated['image_path'] ?? null) ?: '/images/banners/hero-main.jpg';
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/banners'), $filename);
            $imagePath = '/images/banners/' . $filename;
        }

        Banner::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'eyebrow' => $validated['eyebrow'] ?? 'SAVAGEXO ARCHIVE',
            'description' => $validated['description'] ?? null,
            'image_path' => $imagePath,
            'cta_text' => $validated['cta_text'],
            'cta_link' => $validated['cta_link'],
            'position' => $validated['position'],
            'badge_text' => $validated['badge_text'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Homepage banner added.');
    }

    public function toggleBanner(Banner $banner): RedirectResponse
    {
        $banner->update(['is_active' => ! $banner->is_active]);
        return back()->with('success', 'Banner visibility updated.');
    }

    public function deleteBanner(Banner $banner): RedirectResponse
    {
        $banner->delete();
        return back()->with('success', 'Banner deleted.');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_fee_standard' => ['required', 'numeric', 'min:0'],
            'shipping_fee_express' => ['required', 'numeric', 'min:0'],
            'shipping_fee_lagos_same_day' => ['required', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['required', 'numeric', 'min:0'],
            'announcement_text' => ['required', 'string', 'max:350'],
            'contact_email' => ['nullable', 'email', 'max:180'],
            'cod_enabled' => ['nullable', 'boolean'],
        ]);

        StoreSetting::setValue('shipping_fee_standard', $validated['shipping_fee_standard'], 'shipping', 'number');
        StoreSetting::setValue('shipping_fee_express', $validated['shipping_fee_express'], 'shipping', 'number');
        StoreSetting::setValue('shipping_fee_lagos_same_day', $validated['shipping_fee_lagos_same_day'], 'shipping', 'number');
        StoreSetting::setValue('free_shipping_threshold', $validated['free_shipping_threshold'], 'shipping', 'number');
        StoreSetting::setValue('announcement_text', $validated['announcement_text'], 'general', 'text');
        StoreSetting::setValue('contact_email', strtoupper($validated['contact_email'] ?? 'SAVAGEXO@GMAIL.COM'), 'general', 'text');
        StoreSetting::setValue('cod_enabled', $request->boolean('cod_enabled') ? '1' : '0', 'payment', 'boolean');

        return back()->with('success', 'Store delivery rates, contact email, payment options, and announcement bar updated.');
    }
}
