<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\StoreSetting;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Store Settings
        StoreSetting::setValue('shipping_fee_standard', '4500', 'shipping', 'number');
        StoreSetting::setValue('shipping_fee_express', '9500', 'shipping', 'number');
        StoreSetting::setValue('shipping_fee_lagos_same_day', '15000', 'shipping', 'number');
        StoreSetting::setValue('free_shipping_threshold', '250000', 'shipping', 'number');
        StoreSetting::setValue('cod_enabled', '1', 'payment', 'boolean');
        StoreSetting::setValue(
            'announcement_text',
            'LAGOS • LONDON • MILAN — COMPLIMENTARY EXPRESS SHIPPING ON ORDERS OVER ₦250,000 | USE PRIVILEGE CODE: SAVAGE15 FOR 15% OFF',
            'general',
            'text'
        );

        // 2. Users (Admin + Realistic Nigerian Customers)
        $admin = User::updateOrCreate(
            ['email' => 'admin@savagexo.com'],
            [
                'name' => 'Tobi Adeyemi (Creative Director)',
                'phone' => '+234 901 200 8800',
                'password' => Hash::make('SavageAdmin@2026'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now(),
            ]
        );

        $customer1 = User::updateOrCreate(
            ['email' => 'amara.okafor@gmail.com'],
            [
                'name' => 'Amara Okafor',
                'phone' => '+234 803 451 9920',
                'password' => Hash::make('Customer@2026'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(2),
            ]
        );

        $customer2 = User::updateOrCreate(
            ['email' => 'damilola.balogun@gmail.com'],
            [
                'name' => 'Damilola Balogun',
                'phone' => '+234 809 112 4401',
                'password' => Hash::make('Customer@2026'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDay(),
            ]
        );

        $customer3 = User::updateOrCreate(
            ['email' => 'chidi.nwosu@outlook.com'],
            [
                'name' => 'Chidi Nwosu',
                'phone' => '+234 812 889 3012',
                'password' => Hash::make('Customer@2026'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. Customer Addresses
        Address::updateOrCreate(
            ['user_id' => $customer1->id, 'label' => 'Victoria Island Residence'],
            [
                'recipient_name' => 'Amara Okafor',
                'phone' => '+234 803 451 9920',
                'street_address' => '14B Adeola Odeku Street, Victoria Island',
                'apartment' => 'Penthouse 4',
                'city' => 'Lagos',
                'state' => 'Lagos State',
                'postal_code' => '106104',
                'country' => 'Nigeria',
                'is_default' => true,
            ]
        );

        Address::updateOrCreate(
            ['user_id' => $customer1->id, 'label' => 'Ikoyi Studio'],
            [
                'recipient_name' => 'Amara Okafor',
                'phone' => '+234 803 451 9920',
                'street_address' => '22 Bourdillon Road, Ikoyi',
                'apartment' => 'Suite 201',
                'city' => 'Lagos',
                'state' => 'Lagos State',
                'postal_code' => '106104',
                'country' => 'Nigeria',
                'is_default' => false,
            ]
        );

        Address::updateOrCreate(
            ['user_id' => $customer2->id, 'label' => 'Maitama Residence'],
            [
                'recipient_name' => 'Damilola Balogun',
                'phone' => '+234 809 112 4401',
                'street_address' => '8 Danube Street, Maitama',
                'apartment' => 'Duplex B',
                'city' => 'Abuja',
                'state' => 'FCT Abuja',
                'postal_code' => '900271',
                'country' => 'Nigeria',
                'is_default' => true,
            ]
        );

        // 4. Homepage Banners
        Banner::query()->delete();
        Banner::create([
            'title' => 'ARCHITECTURAL SILHOUETTES FOR THE NEW ERA',
            'subtitle' => 'AUTUMN / HARMATTAN ARCHIVE 2026',
            'eyebrow' => 'COLLECTION 04 — LAGOS TO MILAN',
            'description' => 'Uncompromising craftsmanship merging Nigerian sartorial heritage with heavyweight architectural streetwear and tailored Italian wool.',
            'image_path' => '/images/banners/hero-main.jpg',
            'cta_text' => 'Explore Collection',
            'cta_link' => '/shop',
            'secondary_cta_text' => 'Shop Traditional Royalty',
            'secondary_cta_link' => '/shop?category=traditional-wear',
            'position' => 'hero',
            'badge_text' => 'LIMITED ATELIER RELEASE',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'THE SOVEREIGN AGBADA & BESPOKE ROYALTY EDIT',
            'subtitle' => 'HAND-LOOMED IN LAGOS, TAILORED FOR GLOBAL STAGES',
            'eyebrow' => 'HERITAGE COUTURE',
            'description' => 'Each Traditional Wear piece undergoes 48 hours of precision tonal embroidery and hand-finishing in our Victoria Island atelier.',
            'image_path' => '/images/banners/promo-editorial.jpg',
            'cta_text' => 'Discover Traditional Wear',
            'cta_link' => '/shop?category=traditional-wear',
            'position' => 'promo_primary',
            'badge_text' => 'ARTISANAL SERIES',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 5. Categories (All 8 Required Categories)
        $categoriesData = [
            [
                'name' => "Men's Clothing",
                'slug' => 'mens-clothing',
                'gender_group' => 'men',
                'description' => 'Structured overcoats, relaxed wool trousers, and architectural shirting tailored for presence.',
                'image' => '/images/products/mens-tailored-coat.jpg',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => "Women's Clothing",
                'slug' => 'womens-clothing',
                'gender_group' => 'women',
                'description' => 'Fluid raw silk drapery, sculpted corsetry, and commanding evening silhouettes.',
                'image' => '/images/products/womens-silk-drape.jpg',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Streetwear',
                'slug' => 'streetwear',
                'gender_group' => 'unisex',
                'description' => '600GSM organic cotton hoodies, tactical cargo silhouettes, and boxy graphic statements.',
                'image' => '/images/products/streetwear-hoodie.jpg',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Traditional Wear',
                'slug' => 'traditional-wear',
                'gender_group' => 'unisex',
                'description' => 'Contemporary Nigerian Agbada ensembles, Aso-Oke outerwear, and hand-embroidered Kaftans.',
                'image' => '/images/products/agbada-noir.jpg',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sneakers',
                'slug' => 'sneakers',
                'gender_group' => 'unisex',
                'description' => 'Sculpted Italian suede and tumbled calfskin low-tops and monolith runners.',
                'image' => '/images/products/sneaker-monolith.jpg',
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'Shoes',
                'slug' => 'shoes',
                'gender_group' => 'unisex',
                'description' => 'Hand-welted Chelsea boots, architectural loafers, and sculptural heels.',
                'image' => '/images/products/chelsea-boot.jpg',
                'is_featured' => false,
                'sort_order' => 6,
            ],
            [
                'name' => 'Bags',
                'slug' => 'bags',
                'gender_group' => 'accessories',
                'description' => 'Full-grain Saddlery leather totes, structured weekender duffles, and geometric crossbodies.',
                'image' => '/images/products/leather-tote.jpg',
                'is_featured' => false,
                'sort_order' => 7,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'gender_group' => 'accessories',
                'description' => 'Japanese Mazzucchelli acetate eyewear, sterling silver chains, and woven silk scarves.',
                'image' => '/images/products/accessories-eyewear.jpg',
                'is_featured' => false,
                'sort_order' => 8,
            ],
        ];

        $categoryMap = [];
        foreach ($categoriesData as $cat) {
            $category = Category::updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true])
            );
            $categoryMap[$cat['slug']] = $category;
        }

        // 6. Products (16 Curated Luxury Products across all 8 categories)
        $productsData = [
            // Men's Clothing
            [
                'category_slug' => 'mens-clothing',
                'name' => 'Obsidian Double-Breasted Virgin Wool Overcoat',
                'slug' => 'obsidian-double-breasted-virgin-wool-overcoat',
                'sku' => 'SXO-MEN-001',
                'short_description' => 'Architectural drop-shoulder overcoat cut from 750g Italian virgin wool and cashmere.',
                'description' => "Crafted for commanding presence, the Obsidian Double-Breasted Overcoat features an elongated silhouette, hand-padded roped shoulders, and horn buttons engraved with the SAVAGEXO monogram.\n\nFully lined in breathable cupro satin with dual interior welt pockets, this piece transitions effortlessly from formal evening receptions in Ikoyi to winter boulevards in Milan.",
                'price' => 285000,
                'discount_price' => 245000,
                'stock_quantity' => 18,
                'gender' => 'men',
                'material' => '85% Virgin Wool, 15% Cashmere (Lining: 100% Cupro)',
                'care_instructions' => 'Specialist dry clean only. Do not tumble dry.',
                'featured_image' => '/images/products/mens-tailored-coat.jpg',
                'gallery' => ['/images/products/mens-tailored-coat.jpg', '/images/products/agbada-noir.jpg'],
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => [
                    ['name' => 'Obsidian Black', 'hex' => '#111111'],
                    ['name' => 'Espresso Brown', 'hex' => '#3B2820'],
                    ['name' => 'Travertine Sand', 'hex' => '#C9B9A6'],
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'is_trending' => true,
                'sales_count' => 84,
                'views_count' => 640,
            ],
            [
                'category_slug' => 'mens-clothing',
                'name' => 'Atelier Pleated Wide-Leg Gabardine Trousers',
                'slug' => 'atelier-pleated-wide-leg-gabardine-trousers',
                'sku' => 'SXO-MEN-002',
                'short_description' => 'High-waisted double-pleated trousers with fluid drape and concealed buckle waist tabs.',
                'description' => "Cut with a generous wide-leg puddle hem, our Atelier Gabardine Trousers redefine modern tailoring. Constructed from high-twist breathable wool gabardine that resists creasing in tropical and temperate climates alike.",
                'price' => 115000,
                'discount_price' => null,
                'stock_quantity' => 25,
                'gender' => 'men',
                'material' => '100% High-Twist Virgin Wool Gabardine',
                'care_instructions' => 'Dry clean or steam press on low heat.',
                'featured_image' => '/images/products/mens-tailored-coat.jpg',
                'gallery' => ['/images/products/mens-tailored-coat.jpg', '/images/products/streetwear-hoodie.jpg'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => [
                    ['name' => 'Charcoal Noir', 'hex' => '#1E1E1E'],
                    ['name' => 'Bone Ivory', 'hex' => '#EAE5DC'],
                ],
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => false,
                'is_trending' => true,
                'sales_count' => 42,
                'views_count' => 310,
            ],

            // Women's Clothing
            [
                'category_slug' => 'womens-clothing',
                'name' => 'Terracotta Asymmetric Draped Raw Silk Midi Dress',
                'slug' => 'terracotta-asymmetric-draped-raw-silk-midi-dress',
                'sku' => 'SXO-WOM-001',
                'short_description' => 'Bias-cut mulberry silk midi dress with hand-draped cowl neckline and sculptural side slit.',
                'description' => "An ode to liquid movement and warm coastal sunsets. Bias-cut from 28-momme heavyweight mulberry silk, this dress contours the silhouette with an asymmetric cowl back and an adjustable brushed-gold shoulder clasp.",
                'price' => 210000,
                'discount_price' => 185000,
                'stock_quantity' => 14,
                'gender' => 'women',
                'material' => '100% 28-Momme Mulberry Silk',
                'care_instructions' => 'Delicate dry clean only. Steam from reverse side.',
                'featured_image' => '/images/products/womens-silk-drape.jpg',
                'gallery' => ['/images/products/womens-silk-drape.jpg', '/images/products/leather-tote.jpg'],
                'sizes' => ['XS', 'S', 'M', 'L'],
                'colors' => [
                    ['name' => 'Burnt Terracotta', 'hex' => '#B85435'],
                    ['name' => 'Champagne Gold', 'hex' => '#D8C39E'],
                    ['name' => 'Obsidian Black', 'hex' => '#111111'],
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'is_trending' => true,
                'sales_count' => 96,
                'views_count' => 812,
            ],
            [
                'category_slug' => 'womens-clothing',
                'name' => 'Sculpted Basque-Waist Linen Blazer & Palazzo Set',
                'slug' => 'sculpted-basque-waist-linen-blazer-palazzo-set',
                'sku' => 'SXO-WOM-002',
                'short_description' => 'Two-piece architectural suit featuring a cinched waist blazer and floor-sweeping palazzo trousers.',
                'description' => "Engineered with internal corsetry boning through the waist and exaggerated peak lapels, this Belgian linen-silk two-piece commands attention from gallery openings to executive suites.",
                'price' => 260000,
                'discount_price' => null,
                'stock_quantity' => 12,
                'gender' => 'women',
                'material' => '70% Belgian Linen, 30% Tussah Silk',
                'care_instructions' => 'Dry clean only.',
                'featured_image' => '/images/products/womens-silk-drape.jpg',
                'gallery' => ['/images/products/womens-silk-drape.jpg', '/images/products/accessories-eyewear.jpg'],
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'colors' => [
                    ['name' => 'Warm Alabaster', 'hex' => '#F3EFE6'],
                    ['name' => 'Burnt Terracotta', 'hex' => '#B85435'],
                ],
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'is_trending' => false,
                'sales_count' => 65,
                'views_count' => 520,
            ],

            // Streetwear
            [
                'category_slug' => 'streetwear',
                'name' => '600GSM Heavyweight Boxy Cropped Hoodie — Washed Noir',
                'slug' => '600gsm-heavyweight-boxy-cropped-hoodie-washed-noir',
                'sku' => 'SXO-STR-001',
                'short_description' => 'Ultra-dense 600GSM organic French terry hoodie with double-layered hood and tonal puff embroidery.',
                'description' => "Our signature streetwear staple. Knitted from custom-milled 600GSM combed organic cotton and enzyme-washed for a vintage sun-faded patina. Features drop shoulders, a structured double-lined hood without drawstrings, and a metal SAVAGEXO plaque at the nape.",
                'price' => 95000,
                'discount_price' => 78000,
                'stock_quantity' => 40,
                'gender' => 'unisex',
                'material' => '100% Combed Organic Cotton (600 GSM French Terry)',
                'care_instructions' => 'Cold machine wash inside out. Lay flat to dry.',
                'featured_image' => '/images/products/streetwear-hoodie.jpg',
                'gallery' => ['/images/products/streetwear-hoodie.jpg', '/images/products/sneaker-monolith.jpg'],
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'colors' => [
                    ['name' => 'Washed Noir', 'hex' => '#1D1D1F'],
                    ['name' => 'Bone Cream', 'hex' => '#EAE5DC'],
                    ['name' => 'Sage Olive', 'hex' => '#5C6453'],
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'is_trending' => true,
                'sales_count' => 145,
                'views_count' => 1120,
            ],
            [
                'category_slug' => 'streetwear',
                'name' => 'Tactical Multi-Pocket Ripstop Cargo Pant',
                'slug' => 'tactical-multi-pocket-ripstop-cargo-pant',
                'sku' => 'SXO-STR-002',
                'short_description' => 'Japanese cotton ripstop cargo trousers with articulated knees and ankle snap adjusters.',
                'description' => "Built with 8 gusseted bellows pockets, matte cobra belt loops, and concealed ankle toggles so you can wear them stacked over high-top sneakers or wide over monolith runners.",
                'price' => 110000,
                'discount_price' => null,
                'stock_quantity' => 30,
                'gender' => 'unisex',
                'material' => '100% Japanese Cotton Ripstop',
                'care_instructions' => 'Machine wash cold gentle cycle.',
                'featured_image' => '/images/products/streetwear-hoodie.jpg',
                'gallery' => ['/images/products/streetwear-hoodie.jpg'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => [
                    ['name' => 'Obsidian Black', 'hex' => '#111111'],
                    ['name' => 'Sage Olive', 'hex' => '#5C6453'],
                ],
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => false,
                'is_trending' => true,
                'sales_count' => 58,
                'views_count' => 490,
            ],

            // Traditional Wear
            [
                'category_slug' => 'traditional-wear',
                'name' => 'The Sovereign 3-Piece Noir Cashmere Agbada Ensemble',
                'slug' => 'sovereign-3-piece-noir-cashmere-agbada-ensemble',
                'sku' => 'SXO-TRD-001',
                'short_description' => 'Bespoke three-piece Nigerian Agbada with tonal geometric silk embroidery and Sokoto trousers.',
                'description' => "The pinnacle of SAVAGEXO heritage couture. Hand-cut in our Victoria Island atelier, the Sovereign 3-Piece Agbada includes the flowing outer Awosoke robe, tailored long-sleeve Buba tunic, and tapered Sokoto trousers.\n\nEmbellished with 48 hours of intricate tonal geometric embroidery inspired by Benin bronze motifs.",
                'price' => 420000,
                'discount_price' => 375000,
                'stock_quantity' => 10,
                'gender' => 'men',
                'material' => 'Italian Super 150s Wool-Cashmere with Pure Mulberry Silk Embroidery',
                'care_instructions' => 'Specialist couture dry clean only. Store in breathable garment bag.',
                'featured_image' => '/images/products/agbada-noir.jpg',
                'gallery' => ['/images/products/agbada-noir.jpg', '/images/banners/promo-editorial.jpg'],
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => [
                    ['name' => 'Obsidian Black & Gold', 'hex' => '#111111'],
                    ['name' => 'Royal Ivory & Champagne', 'hex' => '#F4EFE6'],
                    ['name' => 'Espresso & Bronze', 'hex' => '#3A261D'],
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'is_trending' => true,
                'sales_count' => 112,
                'views_count' => 980,
            ],
            [
                'category_slug' => 'traditional-wear',
                'name' => 'Hand-Loomed Aso-Oke Kaftan & Wrapper Couture Set',
                'slug' => 'hand-loomed-aso-oke-kaftan-wrapper-couture-set',
                'sku' => 'SXO-TRD-002',
                'short_description' => 'Contemporary metallic-thread Aso-Oke boubou kaftan with matching hand-fringed wrapper.',
                'description' => "Woven by master artisans in Iseyin and tailored in Lagos, this lightweight metallic Aso-Oke kaftan pairs regal heritage with effortless contemporary comfort.",
                'price' => 295000,
                'discount_price' => null,
                'stock_quantity' => 9,
                'gender' => 'women',
                'material' => '100% Hand-Loomed Cotton & Silk Metallic Aso-Oke',
                'care_instructions' => 'Dry clean only.',
                'featured_image' => '/images/products/agbada-noir.jpg',
                'gallery' => ['/images/products/agbada-noir.jpg', '/images/products/womens-silk-drape.jpg'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => [
                    ['name' => 'Champagne Gold', 'hex' => '#D8C39E'],
                    ['name' => 'Burnt Terracotta', 'hex' => '#B85435'],
                ],
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_best_seller' => false,
                'is_trending' => true,
                'sales_count' => 39,
                'views_count' => 410,
            ],

            // Sneakers
            [
                'category_slug' => 'sneakers',
                'name' => 'Monolith Sculpted Suede & Calfskin Low-Top Sneaker',
                'slug' => 'monolith-sculpted-suede-calfskin-low-top-sneaker',
                'sku' => 'SXO-SNK-001',
                'short_description' => 'Hand-lasted Italian suede low-top sneaker on an architectural 45mm Vibram gum cupsole.',
                'description' => "Engineered for all-day comfort and unmistakable visual weight. Features butter-soft calfskin lining, waxed cotton laces, and debossed SAVAGEXO coordinates on the heel counter.",
                'price' => 165000,
                'discount_price' => 142000,
                'stock_quantity' => 22,
                'gender' => 'unisex',
                'material' => '100% Italian Calfskin & Velour Suede Upper, Natural Rubber Sole',
                'care_instructions' => 'Brush with suede eraser and protect with water-repellent spray.',
                'featured_image' => '/images/products/sneaker-monolith.jpg',
                'gallery' => ['/images/products/sneaker-monolith.jpg', '/images/products/streetwear-hoodie.jpg'],
                'sizes' => ['40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Travertine Cream & Gum', 'hex' => '#DFD7C8'],
                    ['name' => 'Obsidian Black', 'hex' => '#111111'],
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'is_trending' => true,
                'sales_count' => 91,
                'views_count' => 760,
            ],
            [
                'category_slug' => 'sneakers',
                'name' => 'Aero-Runner Technical Mesh & Nappa Leather Trainer',
                'slug' => 'aero-runner-technical-mesh-nappa-leather-trainer',
                'sku' => 'SXO-SNK-002',
                'short_description' => 'Multi-panel technical runner combining ballistic nylon mesh with reflective 3M piping.',
                'description' => "A futuristic runner silhouette anchored by a dual-density cushioned midsole and hand-stitched Nappa leather overlays.",
                'price' => 150000,
                'discount_price' => null,
                'stock_quantity' => 16,
                'gender' => 'unisex',
                'material' => 'Nappa Leather & Ballistic Nylon Mesh',
                'care_instructions' => 'Wipe clean with damp cloth.',
                'featured_image' => '/images/products/sneaker-monolith.jpg',
                'gallery' => ['/images/products/sneaker-monolith.jpg'],
                'sizes' => ['39', '40', '41', '42', '43', '44'],
                'colors' => [
                    ['name' => 'Bone Cream', 'hex' => '#EAE5DC'],
                    ['name' => 'Charcoal Noir', 'hex' => '#1E1E1E'],
                ],
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => false,
                'is_trending' => false,
                'sales_count' => 34,
                'views_count' => 290,
            ],

            // Shoes
            [
                'category_slug' => 'shoes',
                'name' => 'Vanguard Hand-Welted Calfskin Chelsea Boot',
                'slug' => 'vanguard-hand-welted-calfskin-chelsea-boot',
                'sku' => 'SXO-SHO-001',
                'short_description' => 'Goodyear-welted full-grain calfskin Chelsea boot with chisel toe and stacked leather heel.',
                'description' => "Handcrafted from vegetable-tanned Tuscan box calfskin, the Vanguard Chelsea Boot pairs a sleek elongated profile with storm-welted durability. Designed to pair seamlessly with both tailored wool trousers and traditional Sokoto cuts.",
                'price' => 195000,
                'discount_price' => 175000,
                'stock_quantity' => 15,
                'gender' => 'men',
                'material' => '100% Full-Grain Tuscan Calfskin Leather',
                'care_instructions' => 'Condition with neutral beeswax cream and cedar shoe trees.',
                'featured_image' => '/images/products/chelsea-boot.jpg',
                'gallery' => ['/images/products/chelsea-boot.jpg', '/images/products/mens-tailored-coat.jpg'],
                'sizes' => ['40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Espresso Brown', 'hex' => '#3B2820'],
                    ['name' => 'Obsidian Black', 'hex' => '#111111'],
                ],
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_best_seller' => true,
                'is_trending' => false,
                'sales_count' => 73,
                'views_count' => 540,
            ],
            [
                'category_slug' => 'shoes',
                'name' => 'Solstice Architectural Square-Toe Leather Mule',
                'slug' => 'solstice-architectural-square-toe-leather-mule',
                'sku' => 'SXO-SHO-002',
                'short_description' => 'Sculpted 75mm geometric heel mule in supple lambskin with padded footbed.',
                'description' => "Minimalist geometry meets cloud-like comfort. The Solstice Mule features an architectural flared block heel and butter-soft lambskin straps.",
                'price' => 135000,
                'discount_price' => null,
                'stock_quantity' => 19,
                'gender' => 'women',
                'material' => '100% Italian Nappa Lambskin',
                'care_instructions' => 'Store in dust bag provided.',
                'featured_image' => '/images/products/chelsea-boot.jpg',
                'gallery' => ['/images/products/chelsea-boot.jpg'],
                'sizes' => ['37', '38', '39', '40', '41'],
                'colors' => [
                    ['name' => 'Cognac Brown', 'hex' => '#7A4326'],
                    ['name' => 'Obsidian Black', 'hex' => '#111111'],
                ],
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => false,
                'is_trending' => true,
                'sales_count' => 47,
                'views_count' => 380,
            ],

            // Bags
            [
                'category_slug' => 'bags',
                'name' => 'The Atelier Saddlery Leather Architectural Tote',
                'slug' => 'atelier-saddlery-leather-architectural-tote',
                'sku' => 'SXO-BAG-001',
                'short_description' => 'Structured full-grain cognac leather carryall with brushed brass hardware and suede interior.',
                'description' => "Designed to carry your 16-inch laptop and travel essentials without losing its crisp geometric silhouette. Hand-stitched with waxed linen thread, hand-painted edges, and solid brushed brass feet.",
                'price' => 230000,
                'discount_price' => 198000,
                'stock_quantity' => 11,
                'gender' => 'unisex',
                'material' => '100% Vegetable-Tanned Full-Grain Saddlery Leather, Suede Lining',
                'care_instructions' => 'Wipe with dry microfiber cloth; condition twice yearly.',
                'featured_image' => '/images/products/leather-tote.jpg',
                'gallery' => ['/images/products/leather-tote.jpg', '/images/products/accessories-eyewear.jpg'],
                'sizes' => ['One Size'],
                'colors' => [
                    ['name' => 'Cognac Saddle', 'hex' => '#8A4B29'],
                    ['name' => 'Obsidian Black', 'hex' => '#111111'],
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'is_trending' => true,
                'sales_count' => 88,
                'views_count' => 690,
            ],
            [
                'category_slug' => 'bags',
                'name' => 'Nomad Weekender Pebble-Grain Leather Duffle',
                'slug' => 'nomad-weekender-pebble-grain-leather-duffle',
                'sku' => 'SXO-BAG-002',
                'short_description' => 'Cabin-approved luxury travel duffle with detachable padded shoulder strap and shoe compartment.',
                'description' => "Engineered for private charters and weekend escapes between Lagos, Abuja, and London. Includes a dedicated waterproof shoe compartment and lockable solid brass dual zippers.",
                'price' => 340000,
                'discount_price' => null,
                'stock_quantity' => 8,
                'gender' => 'unisex',
                'material' => '100% Pebble-Grain Calfskin Leather',
                'care_instructions' => 'Keep away from direct moisture; store stuffed with tissue.',
                'featured_image' => '/images/products/leather-tote.jpg',
                'gallery' => ['/images/products/leather-tote.jpg'],
                'sizes' => ['One Size'],
                'colors' => [
                    ['name' => 'Obsidian Black', 'hex' => '#111111'],
                    ['name' => 'Espresso Brown', 'hex' => '#3B2820'],
                ],
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_best_seller' => false,
                'is_trending' => true,
                'sales_count' => 29,
                'views_count' => 340,
            ],

            // Accessories
            [
                'category_slug' => 'accessories',
                'name' => 'Kengo Sculptural Acetate Frame Sunglasses',
                'slug' => 'kengo-sculptural-acetate-frame-sunglasses',
                'sku' => 'SXO-ACC-001',
                'short_description' => '8mm Japanese Mazzucchelli cellulose acetate frames with UV400 polarized CR-39 lenses.',
                'description' => "Hand-polished for 72 hours in wooden tumbling barrels, the Kengo Sunglasses feature exposed gold-plated wire cores, five-barrel titanium hinges, and 100% UVA/UVB polarized sun lenses.",
                'price' => 85000,
                'discount_price' => 72000,
                'stock_quantity' => 35,
                'gender' => 'unisex',
                'material' => 'Japanese Cellulose Acetate & Titanium Hinges',
                'care_instructions' => 'Clean with included SAVAGEXO microfiber cloth and hard leather case.',
                'featured_image' => '/images/products/accessories-eyewear.jpg',
                'gallery' => ['/images/products/accessories-eyewear.jpg', '/images/products/leather-tote.jpg'],
                'sizes' => ['One Size'],
                'colors' => [
                    ['name' => 'Havana Tortoiseshell', 'hex' => '#593825'],
                    ['name' => 'Obsidian Black', 'hex' => '#111111'],
                ],
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_best_seller' => true,
                'is_trending' => true,
                'sales_count' => 130,
                'views_count' => 910,
            ],
            [
                'category_slug' => 'accessories',
                'name' => '18K Vermeil Heavy Cuban Link Monogram Necklace',
                'slug' => '18k-vermeil-heavy-cuban-link-monogram-necklace',
                'sku' => 'SXO-ACC-002',
                'short_description' => 'Solid 925 sterling silver 10mm Cuban chain plated in 3-micron 18K yellow gold with box clasp.',
                'description' => "Weighty, water-resistant, and precision-engraved with the SAVAGEXO interlocking clasp.",
                'price' => 125000,
                'discount_price' => null,
                'stock_quantity' => 20,
                'gender' => 'unisex',
                'material' => '925 Sterling Silver with 18K Gold Vermeil',
                'care_instructions' => 'Polish gently with jewelry cloth.',
                'featured_image' => '/images/products/accessories-eyewear.jpg',
                'gallery' => ['/images/products/accessories-eyewear.jpg'],
                'sizes' => ['20 Inch', '22 Inch'],
                'colors' => [
                    ['name' => '18K Yellow Gold', 'hex' => '#D4AF37'],
                    ['name' => 'Brushed Sterling Silver', 'hex' => '#C0C0C0'],
                ],
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_best_seller' => false,
                'is_trending' => false,
                'sales_count' => 51,
                'views_count' => 420,
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $pData) {
            $category = $categoryMap[$pData['category_slug']];

            $product = Product::updateOrCreate(
                ['slug' => $pData['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $pData['name'],
                    'sku' => $pData['sku'],
                    'short_description' => $pData['short_description'],
                    'description' => $pData['description'],
                    'price' => $pData['price'],
                    'discount_price' => $pData['discount_price'],
                    'stock_quantity' => $pData['stock_quantity'],
                    'gender' => $pData['gender'],
                    'material' => $pData['material'],
                    'care_instructions' => $pData['care_instructions'],
                    'featured_image' => $pData['featured_image'],
                    'is_featured' => $pData['is_featured'],
                    'is_new_arrival' => $pData['is_new_arrival'],
                    'is_best_seller' => $pData['is_best_seller'],
                    'is_trending' => $pData['is_trending'],
                    'is_active' => true,
                    'sales_count' => $pData['sales_count'],
                    'views_count' => $pData['views_count'],
                    'meta_title' => $pData['name'] . ' | SAVAGEXO CLOTHINGS',
                    'meta_description' => $pData['short_description'],
                ]
            );

            $product->images()->delete();
            foreach ($pData['gallery'] as $idx => $imgPath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgPath,
                    'alt_text' => $product->name . ' view ' . ($idx + 1),
                    'is_primary' => $idx === 0,
                    'sort_order' => $idx + 1,
                ]);
            }

            $product->allVariants()->delete();
            foreach ($pData['sizes'] as $size) {
                foreach ($pData['colors'] as $c) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $c['name'],
                        'color_hex' => $c['hex'],
                        'sku_variant' => $product->sku . '-' . strtoupper($size) . '-' . strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $c['name']), 0, 3)),
                        'stock_quantity' => 10,
                        'price_adjustment' => 0,
                        'is_active' => true,
                    ]);
                }
            }

            $createdProducts[] = $product;
        }

        // 7. Discount Coupons
        Coupon::updateOrCreate(
            ['code' => 'SAVAGE15'],
            [
                'description' => '15% Welcome Privilege Discount on orders over ₦80,000',
                'type' => 'percentage',
                'value' => 15,
                'min_order_amount' => 80000,
                'max_discount_amount' => 75000,
                'usage_limit' => 500,
                'used_count' => 18,
                'starts_at' => now()->subMonth(),
                'expires_at' => now()->addYear(),
                'is_active' => true,
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'LAGOSVIP'],
            [
                'description' => '₦25,000 Private Client Rebate on orders over ₦200,000',
                'type' => 'fixed',
                'value' => 25000,
                'min_order_amount' => 200000,
                'max_discount_amount' => 25000,
                'usage_limit' => 200,
                'used_count' => 9,
                'starts_at' => now()->subMonth(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'ROYALTY10'],
            [
                'description' => '10% Storewide Discount — No Minimum Spend',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 0,
                'max_discount_amount' => 50000,
                'usage_limit' => 1000,
                'used_count' => 41,
                'starts_at' => now()->subMonth(),
                'expires_at' => now()->addYear(),
                'is_active' => true,
            ]
        );

        // 8. Sample Orders & Payments
        Order::query()->delete();

        $order1 = Order::create([
            'order_number' => 'SXO-261110-A89K2',
            'tracking_number' => 'TRK-SXO-8849201A',
            'user_id' => $customer1->id,
            'customer_name' => $customer1->name,
            'customer_email' => $customer1->email,
            'customer_phone' => $customer1->phone,
            'shipping_address' => '14B Adeola Odeku Street, Victoria Island',
            'shipping_city' => 'Lagos',
            'shipping_state' => 'Lagos State',
            'shipping_postal_code' => '106104',
            'shipping_country' => 'Nigeria',
            'shipping_method' => 'lagos_same_day',
            'subtotal' => 430000,
            'discount_amount' => 25000,
            'coupon_code' => 'LAGOSVIP',
            'shipping_fee' => 15000,
            'total_amount' => 420000,
            'payment_method' => 'paystack',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
            'paid_at' => now()->subDays(5),
            'shipped_at' => now()->subDays(4),
            'delivered_at' => now()->subDays(3),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $createdProducts[0]->id,
            'product_name' => $createdProducts[0]->name,
            'product_sku' => $createdProducts[0]->sku,
            'product_image' => $createdProducts[0]->featured_image,
            'size' => 'L',
            'color' => 'Obsidian Black',
            'quantity' => 1,
            'unit_price' => 245000,
            'line_total' => 245000,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $createdProducts[2]->id,
            'product_name' => $createdProducts[2]->name,
            'product_sku' => $createdProducts[2]->sku,
            'product_image' => $createdProducts[2]->featured_image,
            'size' => 'M',
            'color' => 'Burnt Terracotta',
            'quantity' => 1,
            'unit_price' => 185000,
            'line_total' => 185000,
        ]);

        Payment::create([
            'order_id' => $order1->id,
            'user_id' => $customer1->id,
            'gateway' => 'paystack',
            'transaction_reference' => 'SXO-PSK-99283741-1',
            'gateway_reference' => 'PSK_LIVE_8839201',
            'amount' => 420000,
            'currency' => 'NGN',
            'status' => 'success',
            'channel' => 'card',
            'gateway_response' => ['status' => 'success', 'bank' => 'Zenith Bank PLC', 'card_type' => 'mastercard DEBIT'],
            'verified_at' => now()->subDays(5),
        ]);

        $order2 = Order::create([
            'order_number' => 'SXO-261112-B74M9',
            'tracking_number' => 'TRK-SXO-7731904B',
            'user_id' => $customer2->id,
            'customer_name' => $customer2->name,
            'customer_email' => $customer2->email,
            'customer_phone' => $customer2->phone,
            'shipping_address' => '8 Danube Street, Maitama',
            'shipping_city' => 'Abuja',
            'shipping_state' => 'FCT Abuja',
            'shipping_postal_code' => '900271',
            'shipping_country' => 'Nigeria',
            'shipping_method' => 'express',
            'subtotal' => 375000,
            'discount_amount' => 0,
            'shipping_fee' => 9500,
            'total_amount' => 384500,
            'payment_method' => 'flutterwave',
            'payment_status' => 'paid',
            'order_status' => 'shipped',
            'paid_at' => now()->subDay(),
            'shipped_at' => now()->subHours(8),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $createdProducts[6]->id,
            'product_name' => $createdProducts[6]->name,
            'product_sku' => $createdProducts[6]->sku,
            'product_image' => $createdProducts[6]->featured_image,
            'size' => 'XL',
            'color' => 'Obsidian Black & Gold',
            'quantity' => 1,
            'unit_price' => 375000,
            'line_total' => 375000,
        ]);

        Payment::create([
            'order_id' => $order2->id,
            'user_id' => $customer2->id,
            'gateway' => 'flutterwave',
            'transaction_reference' => 'SXO-FLW-55192834-2',
            'gateway_reference' => 'FLW_TX_9940123',
            'amount' => 384500,
            'currency' => 'NGN',
            'status' => 'success',
            'channel' => 'bank_transfer',
            'gateway_response' => ['status' => 'successful', 'processor_response' => 'Approved'],
            'verified_at' => now()->subDay(),
        ]);

        $order3 = Order::create([
            'order_number' => 'SXO-261113-C19X4',
            'tracking_number' => 'TRK-SXO-6620198C',
            'user_id' => $customer1->id,
            'customer_name' => $customer1->name,
            'customer_email' => $customer1->email,
            'customer_phone' => $customer1->phone,
            'shipping_address' => '14B Adeola Odeku Street, Victoria Island',
            'shipping_city' => 'Lagos',
            'shipping_state' => 'Lagos State',
            'shipping_postal_code' => '106104',
            'shipping_country' => 'Nigeria',
            'shipping_method' => 'standard',
            'subtotal' => 220000,
            'discount_amount' => 0,
            'shipping_fee' => 4500,
            'total_amount' => 224500,
            'payment_method' => 'paystack',
            'payment_status' => 'paid',
            'order_status' => 'processing',
            'paid_at' => now()->subHours(3),
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $createdProducts[4]->id,
            'product_name' => $createdProducts[4]->name,
            'product_sku' => $createdProducts[4]->sku,
            'product_image' => $createdProducts[4]->featured_image,
            'size' => 'M',
            'color' => 'Washed Noir',
            'quantity' => 1,
            'unit_price' => 78000,
            'line_total' => 78000,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $createdProducts[8]->id,
            'product_name' => $createdProducts[8]->name,
            'product_sku' => $createdProducts[8]->sku,
            'product_image' => $createdProducts[8]->featured_image,
            'size' => '42',
            'color' => 'Travertine Cream & Gum',
            'quantity' => 1,
            'unit_price' => 142000,
            'line_total' => 142000,
        ]);

        Payment::create([
            'order_id' => $order3->id,
            'user_id' => $customer1->id,
            'gateway' => 'paystack',
            'transaction_reference' => 'SXO-PSK-33491022-3',
            'gateway_reference' => 'PSK_LIVE_3349102',
            'amount' => 224500,
            'currency' => 'NGN',
            'status' => 'success',
            'channel' => 'card',
            'gateway_response' => ['status' => 'success', 'bank' => 'Guaranty Trust Bank'],
            'verified_at' => now()->subHours(3),
        ]);

        // 9. Customer Reviews & Testimonials
        Review::query()->delete();
        Review::create([
            'product_id' => $createdProducts[6]->id,
            'user_id' => $customer2->id,
            'order_id' => $order2->id,
            'rating' => 5,
            'title' => 'Unmatched Nigerian luxury craftsmanship',
            'comment' => 'Wore the Sovereign 3-Piece Noir Cashmere Agbada to a wedding in Victoria Island and a gala in London. The drape of the Super 150s wool and the precision of the tonal embroidery rival any European couture house.',
            'is_verified_purchase' => true,
            'is_approved' => true,
        ]);

        Review::create([
            'product_id' => $createdProducts[2]->id,
            'user_id' => $customer1->id,
            'order_id' => $order1->id,
            'rating' => 5,
            'title' => 'Liquid silk perfection & same-day Lagos delivery',
            'comment' => 'The 28-momme mulberry silk feels extraordinary on the skin. Ordered at 10 AM and the SAVAGEXO concierge courier delivered it in a custom garment box to my apartment in VI by 3 PM.',
            'is_verified_purchase' => true,
            'is_approved' => true,
        ]);

        Review::create([
            'product_id' => $createdProducts[4]->id,
            'user_id' => $customer3->id,
            'order_id' => null,
            'rating' => 5,
            'title' => 'True 600GSM heavyweight architecture',
            'comment' => 'Finally a Nigerian fashion house doing genuine heavyweight luxury streetwear. The boxy crop, double-layered hood, and washed noir patina are 10/10.',
            'is_verified_purchase' => true,
            'is_approved' => true,
        ]);

        Review::create([
            'product_id' => $createdProducts[0]->id,
            'user_id' => $customer1->id,
            'order_id' => $order1->id,
            'rating' => 5,
            'title' => 'Tailoring on another level',
            'comment' => 'The shoulder structure and cashmere-wool blend on the Obsidian Overcoat feel bespoke. Worth every Naira.',
            'is_verified_purchase' => true,
            'is_approved' => true,
        ]);

        // 10. Wishlists
        Wishlist::firstOrCreate(['user_id' => $customer1->id, 'product_id' => $createdProducts[6]->id]);
        Wishlist::firstOrCreate(['user_id' => $customer1->id, 'product_id' => $createdProducts[12]->id]);

        // 11. Run Full Sneaker Brands & Multi-Angle Clothing Gallery Upgrade
        $this->call(CatalogUpgradeSeeder::class);
    }
}
