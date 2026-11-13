<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class CatalogUpgradeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Update Contact Email Setting
        StoreSetting::setValue('contact_email', 'SAVAGEXO@GMAIL.COM', 'general', 'text');
        StoreSetting::setValue('support_email', 'SAVAGEXO@GMAIL.COM', 'general', 'text');

        // 2. Seed All 16 Sneaker & Fashion Brands
        $brandsData = [
            ['name' => 'Nike', 'slug' => 'nike', 'type' => 'sneaker', 'origin_country' => 'USA', 'description' => 'Iconic Air cushioning, Swoosh heritage, and court-to-street silhouettes.', 'sort_order' => 1],
            ['name' => 'Adidas', 'slug' => 'adidas', 'type' => 'sneaker', 'origin_country' => 'Germany', 'description' => 'Three-Stripes terrace classics, Samba archives, and Boost performance engineering.', 'sort_order' => 2],
            ['name' => 'Puma', 'slug' => 'puma', 'type' => 'sneaker', 'origin_country' => 'Germany', 'description' => 'Formstrip suede legends, RS-X tech runners, and Italian terrace icons.', 'sort_order' => 3],
            ['name' => 'Jordan', 'slug' => 'jordan', 'type' => 'sneaker', 'origin_country' => 'USA', 'description' => 'Championship flight heritage, High OG leather cuts, and hardwood luxury.', 'sort_order' => 4],
            ['name' => 'New Balance', 'slug' => 'new-balance', 'type' => 'sneaker', 'origin_country' => 'USA', 'description' => 'Sculpted suede craftsmanship, 9060 futuristic runners, and 550 court classics.', 'sort_order' => 5],
            ['name' => 'Asics', 'slug' => 'asics', 'type' => 'sneaker', 'origin_country' => 'Japan', 'description' => 'Japanese GEL cushioning technology and metallic Y2K technical runners.', 'sort_order' => 6],
            ['name' => 'Converse', 'slug' => 'converse', 'type' => 'sneaker', 'origin_country' => 'USA', 'description' => 'Timeless Chuck 70 heavyweight canvas high-tops and vulcanized heritage.', 'sort_order' => 7],
            ['name' => 'Vans', 'slug' => 'vans', 'type' => 'sneaker', 'origin_country' => 'USA', 'description' => 'California skate culture, jazz-stripe suede low-tops, and waffle outsoles.', 'sort_order' => 8],
            ['name' => 'Reebok', 'slug' => 'reebok', 'type' => 'sneaker', 'origin_country' => 'UK', 'description' => '1980s garment leather classics and clean British athletic heritage.', 'sort_order' => 9],
            ['name' => 'Salomon', 'slug' => 'salomon', 'type' => 'sneaker', 'origin_country' => 'France', 'description' => 'French Alpine technical trail architecture, Quicklace systems, and GORE-TEX protection.', 'sort_order' => 10],
            ['name' => 'On Running', 'slug' => 'on-running', 'type' => 'sneaker', 'origin_country' => 'Switzerland', 'description' => 'Swiss-engineered CloudTec pods and Helion superfoam road runners.', 'sort_order' => 11],
            ['name' => 'Skechers', 'slug' => 'skechers', 'type' => 'sneaker', 'origin_country' => 'USA', 'description' => 'Arch-Fit comfort technology and lightweight everyday lifestyle trainers.', 'sort_order' => 12],
            ['name' => 'Under Armour', 'slug' => 'under-armour', 'type' => 'sneaker', 'origin_country' => 'USA', 'description' => 'High-intensity HOVR training footwear and court-ready lockdown.', 'sort_order' => 13],
            ['name' => 'Fila', 'slug' => 'fila', 'type' => 'sneaker', 'origin_country' => 'Italy', 'description' => 'Biella Italian sportswear heritage and chunky retro street silhouettes.', 'sort_order' => 14],
            ['name' => 'Yeezy', 'slug' => 'yeezy', 'type' => 'sneaker', 'origin_country' => 'USA', 'description' => 'Independent secondary-market curated architectural knit and foam silhouettes.', 'sort_order' => 15],
            ['name' => 'SAVAGEXO Atelier', 'slug' => 'savagexo', 'type' => 'luxury', 'origin_country' => 'Nigeria', 'description' => 'In-house Victoria Island luxury ready-to-wear, Agbada couture, and Italian leather goods.', 'sort_order' => 16],
        ];

        $brandsMap = [];
        foreach ($brandsData as $b) {
            $brand = Brand::updateOrCreate(
                ['slug' => $b['slug']],
                array_merge($b, ['is_featured' => true, 'is_active' => true])
            );
            $brandsMap[$b['name']] = $brand;
        }

        // 3. Add Dedicated Sneaker Hero Banner
        Banner::updateOrCreate(
            ['title' => 'THE GLOBAL SNEAKER ARCHIVE — NIKE, ADIDAS, PUMA & NEW BALANCE'],
            [
                'subtitle' => 'CURATED STREETWEAR & PERFORMANCE FOOTWEAR',
                'eyebrow' => 'SNEAKER DESTINATION // LAGOS',
                'description' => 'Explore 20+ iconic silhouettes across Running, Basketball, Lifestyle, Retro Terrace, and Luxury Streetwear — ready for immediate dispatch.',
                'image_path' => '/images/sneakers/air-jordan-1-retro-high-og-lateral.jpg',
                'cta_text' => 'Explore Sneaker Vault',
                'cta_link' => '/sneakers',
                'secondary_cta_text' => 'Shop Nike Collection',
                'secondary_cta_link' => '/sneakers?brand=nike',
                'position' => 'sneaker_hero',
                'badge_text' => 'CURATED FOOTWEAR ARCHIVE',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 4. Upgrade All Existing Clothing Products with 6 Multi-Angle Pictures Each
        $clothingPrefixMap = [
            'obsidian-double-breasted-virgin-wool-overcoat' => 'coat',
            'atelier-pleated-wide-leg-gabardine-trousers' => 'trousers',
            'terracotta-asymmetric-draped-raw-silk-midi-dress' => 'silk',
            'sculpted-basque-waist-linen-blazer-palazzo-set' => 'blazer',
            '600gsm-heavyweight-boxy-cropped-hoodie-washed-noir' => 'hoodie',
            'tactical-multi-pocket-ripstop-cargo-pant' => 'cargo',
            'sovereign-3-piece-noir-cashmere-agbada-ensemble' => 'agbada',
            'hand-loomed-aso-oke-kaftan-wrapper-couture-set' => 'kaftan',
            'vanguard-hand-welted-calfskin-chelsea-boot' => 'boot',
            'solstice-architectural-square-toe-leather-mule' => 'mule',
            'atelier-saddlery-leather-architectural-tote' => 'tote',
            'nomad-weekender-pebble-grain-leather-duffle' => 'duffle',
            'kengo-sculptural-acetate-frame-sunglasses' => 'eyewear',
            '18k-vermeil-heavy-cuban-link-monogram-necklace' => 'chain',
        ];

        $savagexoBrand = $brandsMap['SAVAGEXO Atelier'];

        foreach ($clothingPrefixMap as $slug => $prefix) {
            $product = Product::where('slug', $slug)->first();
            if (! $product) {
                continue;
            }

            $colors = $product->available_colors;
            $primaryColor = $colors[0]['name'] ?? 'Obsidian Black';
            $secondaryColor = $colors[1]['name'] ?? $primaryColor;

            $product->update([
                'brand_id' => $savagexoBrand->id,
                'brand_name' => 'SAVAGEXO Atelier',
                'product_type' => in_array($prefix, ['tote', 'duffle', 'eyewear', 'chain'], true) ? 'accessory' : 'clothing',
                'featured_image' => "/images/products/{$prefix}-front.jpg",
            ]);

            $product->images()->delete();

            $angleDefinitions = [
                ['file' => "/images/products/{$prefix}-front.jpg", 'angle' => 'Front View', 'color' => $primaryColor, 'primary' => true],
                ['file' => "/images/products/{$prefix}-back.jpg", 'angle' => 'Back View', 'color' => $primaryColor, 'primary' => false],
                ['file' => "/images/products/{$prefix}-side.jpg", 'angle' => 'Side Profile View', 'color' => $primaryColor, 'primary' => false],
                ['file' => "/images/products/{$prefix}-detail.jpg", 'angle' => 'Close-Up Fabric & Stitching Detail', 'color' => $primaryColor, 'primary' => false],
                ['file' => "/images/products/{$prefix}-model.jpg", 'angle' => 'Model Editorial Lookbook', 'color' => $primaryColor, 'primary' => false],
                ['file' => "/images/products/{$prefix}-altcolor.jpg", 'angle' => 'Alternate Colorway (' . $secondaryColor . ')', 'color' => $secondaryColor, 'primary' => false],
            ];

            foreach ($angleDefinitions as $idx => $def) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $def['file'],
                    'alt_text' => $product->name . ' — ' . $def['angle'],
                    'view_angle' => $def['angle'],
                    'color_name' => $def['color'],
                    'is_primary' => $def['primary'],
                    'sort_order' => $idx + 1,
                ]);
            }
        }

        // Also update the 2 existing SAVAGEXO sneakers with brand_id and 5 images
        $sneakerCategory = Category::where('slug', 'sneakers')->firstOrFail();
        $existingSneakers = Product::where('category_id', $sneakerCategory->id)
            ->whereIn('slug', [
                'monolith-sculpted-suede-calfskin-low-top-sneaker',
                'aero-runner-technical-mesh-nappa-leather-trainer',
            ])->get();

        foreach ($existingSneakers as $exSnk) {
            $exSnk->update([
                'brand_id' => $savagexoBrand->id,
                'brand_name' => 'SAVAGEXO Atelier',
                'sneaker_style' => 'Luxury',
                'product_type' => 'sneaker',
            ]);
            if ($exSnk->images()->count() < 4) {
                $exSnk->images()->delete();
                $fallbackAngles = [
                    ['path' => '/images/products/sneaker-monolith.jpg', 'angle' => 'Lateral Side Profile'],
                    ['path' => '/images/sneakers/new-balance-9060-medial.jpg', 'angle' => 'Medial Profile'],
                    ['path' => '/images/sneakers/new-balance-9060-heel.jpg', 'angle' => 'Heel & Sole View'],
                    ['path' => '/images/sneakers/new-balance-9060-detail.jpg', 'angle' => 'Suede & Calfskin Detail'],
                    ['path' => '/images/sneakers/new-balance-9060-onfoot.jpg', 'angle' => 'Editorial On-Foot'],
                ];
                foreach ($fallbackAngles as $i => $fa) {
                    ProductImage::create([
                        'product_id' => $exSnk->id,
                        'image_path' => $fa['path'],
                        'alt_text' => $exSnk->name . ' — ' . $fa['angle'],
                        'view_angle' => $fa['angle'],
                        'color_name' => 'Travertine Cream & Gum',
                        'is_primary' => $i === 0,
                        'sort_order' => $i + 1,
                    ]);
                }
            }
        }

        // 5. Seed 20 Curated Multi-Brand Sneaker Products (Each with 5 Multi-Angle Pictures)
        $sneakerProducts = [
            [
                'brand' => 'Nike',
                'name' => "Nike Air Force 1 '07 Low Triple White & Obsidian",
                'slug' => 'nike-air-force-1-07',
                'sku' => 'SXO-NK-AF1',
                'style' => 'Lifestyle',
                'price' => 175000,
                'discount_price' => 158000,
                'stock' => 32,
                'gender' => 'unisex',
                'short' => 'Crisp stitched leather overlays, perforated toe box, and full-length Nike Air cushioning.',
                'desc' => "The unmistakable street icon since 1982. Constructed with smooth tumbled leather uppers, a padded low-cut collar, and a durable cupsole with pivot-circle traction.\n\nIndependent Curated Notice: Sourced through verified independent sneaker distribution partners; representative studio silhouette photography shown.",
                'sizes' => ['39', '40', '41', '42', '43', '44', '45', '46'],
                'colors' => [
                    ['name' => 'Triple White', 'hex' => '#F8F8F6'],
                    ['name' => 'Obsidian Black', 'hex' => '#141416'],
                ],
                'new' => true,
                'best' => true,
                'trending' => true,
                'sales' => 210,
            ],
            [
                'brand' => 'Nike',
                'name' => 'Nike Air Max 90 Infrared OG Mesh & Leather',
                'slug' => 'nike-air-max-90',
                'sku' => 'SXO-NK-AM90',
                'style' => 'Running',
                'price' => 195000,
                'discount_price' => 178000,
                'stock' => 24,
                'gender' => 'unisex',
                'short' => 'Tinker Hatfield’s 1990 running masterpiece with visible Max Air heel cassette and waffle outsole.',
                'desc' => "Featuring breathable ballistic mesh, stitched leather mudguards, TPU heel accents, and the iconic Radiant Red / Infrared window frame.\n\nIndependent Curated Notice: Independently curated sneaker catalog item; not affiliated with or endorsed by the trademark owner.",
                'sizes' => ['40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Infrared & Wolf Grey', 'hex' => '#D73A2A'],
                    ['name' => 'Obsidian Noir', 'hex' => '#18181B'],
                ],
                'new' => true,
                'best' => true,
                'trending' => true,
                'sales' => 164,
            ],
            [
                'brand' => 'Nike',
                'name' => 'Nike Dunk Low Retro Black White Panda',
                'slug' => 'nike-dunk-low-panda',
                'sku' => 'SXO-NK-DNK',
                'style' => 'Streetwear',
                'price' => 180000,
                'discount_price' => 162000,
                'stock' => 28,
                'gender' => 'unisex',
                'short' => '80s hardwood color-blocking in crisp white leather with stark black overlays.',
                'desc' => "Originally created for the college hardwood, the Dunk Low Retro brings vintage hoops flair back to the streets of Lagos and London with a lightweight foam midsole and padded low-top collar.",
                'sizes' => ['38', '39', '40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Black / White Panda', 'hex' => '#141414'],
                    ['name' => 'Cacao Wow & Sail', 'hex' => '#5C3A28'],
                ],
                'new' => false,
                'best' => true,
                'trending' => true,
                'sales' => 245,
            ],
            [
                'brand' => 'Jordan',
                'name' => 'Air Jordan 1 Retro High OG Lost & Found Chicago',
                'slug' => 'air-jordan-1-retro-high-og',
                'sku' => 'SXO-JD-AJ1',
                'style' => 'Basketball',
                'price' => 345000,
                'discount_price' => 315000,
                'stock' => 12,
                'gender' => 'unisex',
                'short' => 'High-top full-grain leather icon in Varsity Red, Sail, and Muslin with Wings collar logo.',
                'desc' => "The silhouette that started sneaker culture. Cut in high-top OG specification with cracked leather accents, vintage muslin tongue, and encapsulated Air-Sole unit.",
                'sizes' => ['40', '41', '42', '43', '44', '45', '46'],
                'colors' => [
                    ['name' => 'Varsity Red / White / Black', 'hex' => '#BE2026'],
                    ['name' => 'Royal Blue / Black', 'hex' => '#1E40AF'],
                ],
                'new' => true,
                'best' => true,
                'trending' => true,
                'sales' => 190,
            ],
            [
                'brand' => 'Nike',
                'name' => 'Nike Air Zoom Pegasus 41 ReactX Road Runner',
                'slug' => 'nike-air-zoom-pegasus-41',
                'sku' => 'SXO-NK-PEG41',
                'style' => 'Running',
                'price' => 185000,
                'discount_price' => null,
                'stock' => 20,
                'gender' => 'unisex',
                'short' => 'Dual Air Zoom units paired with responsive ReactX foam midsole and engineered mesh.',
                'desc' => "Built for daily distance runners and high-energy city movement. Delivers 13% greater energy return with forefoot and heel Air Zoom pods.",
                'sizes' => ['40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Anthracite & Volt', 'hex' => '#22262C'],
                    ['name' => 'Pure Platinum', 'hex' => '#E5E7EB'],
                ],
                'new' => true,
                'best' => false,
                'trending' => false,
                'sales' => 78,
            ],
            [
                'brand' => 'Adidas',
                'name' => 'Adidas Originals Samba OG Cloud White & Gum',
                'slug' => 'adidas-samba-og',
                'sku' => 'SXO-AD-SMB',
                'style' => 'Lifestyle',
                'price' => 165000,
                'discount_price' => 148000,
                'stock' => 35,
                'gender' => 'unisex',
                'short' => 'Supple leather upper, suede T-toe overlay, serrated 3-Stripes, and classic gum rubber cupsole.',
                'desc' => "Born on the pitch in the 1950s and adopted by global fashion weeks. The Samba OG pairs effortlessly with wide-leg wool trousers, denim, or relaxed streetwear.",
                'sizes' => ['38', '39', '40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Cloud White / Core Black', 'hex' => '#F6F5F0'],
                    ['name' => 'Core Black / Gum', 'hex' => '#161618'],
                ],
                'new' => true,
                'best' => true,
                'trending' => true,
                'sales' => 230,
            ],
            [
                'brand' => 'Adidas',
                'name' => 'Adidas Originals Superstar 82 Shell-Toe Vintage',
                'slug' => 'adidas-superstar-82',
                'sku' => 'SXO-AD-SUP',
                'style' => 'Retro',
                'price' => 155000,
                'discount_price' => null,
                'stock' => 22,
                'gender' => 'unisex',
                'short' => '1982 proportions with vintage cream rubber shell toe and gold foil tongue branding.',
                'desc' => "An homage to hip-hop and basketball history, crafted from premium smooth leather with retro off-white shell toe and herringbone cupsole.",
                'sizes' => ['39', '40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Chalk White / Core Black', 'hex' => '#F4F1EA'],
                    ['name' => 'Triple Black', 'hex' => '#141414'],
                ],
                'new' => false,
                'best' => true,
                'trending' => false,
                'sales' => 118,
            ],
            [
                'brand' => 'Adidas',
                'name' => 'Adidas Ultraboost Light Primeknit Performance',
                'slug' => 'adidas-ultraboost-light',
                'sku' => 'SXO-AD-UBL',
                'style' => 'Running',
                'price' => 225000,
                'discount_price' => 199000,
                'stock' => 18,
                'gender' => 'unisex',
                'short' => '30% lighter BOOST cushioning with PRIMEKNIT+FORGED upper and Continental rubber outsole.',
                'desc' => "Engineered with hundreds of BOOST capsules for explosive energy return and sock-like lockdown.",
                'sizes' => ['40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Core Black & Terracotta', 'hex' => '#1C1C20'],
                    ['name' => 'Cloud White', 'hex' => '#F5F5F5'],
                ],
                'new' => true,
                'best' => false,
                'trending' => true,
                'sales' => 94,
            ],
            [
                'brand' => 'Adidas',
                'name' => 'Adidas Campus 00s Heavy Suede & Fat Lace Skate',
                'slug' => 'adidas-campus-00s',
                'sku' => 'SXO-AD-CMP00',
                'style' => 'Streetwear',
                'price' => 168000,
                'discount_price' => 152000,
                'stock' => 26,
                'gender' => 'unisex',
                'short' => 'Y2K skate-inspired proportions with plush padded tongue, wide laces, and velour suede.',
                'desc' => "Recut with bold 2000s proportions, the Campus 00s brings heavy suede texture and oversized 3-Stripes to modern baggy silhouettes.",
                'sizes' => ['38', '39', '40', '41', '42', '43', '44'],
                'colors' => [
                    ['name' => 'Forest Olive & Off-White', 'hex' => '#363E34'],
                    ['name' => 'Charcoal Grey & Gum', 'hex' => '#4B5563'],
                ],
                'new' => true,
                'best' => true,
                'trending' => true,
                'sales' => 152,
            ],
            [
                'brand' => 'Puma',
                'name' => 'Puma Suede Classic XXI Formstrip Heritage',
                'slug' => 'puma-suede-classic-xxi',
                'sku' => 'SXO-PM-SUE',
                'style' => 'Casual',
                'price' => 135000,
                'discount_price' => 120000,
                'stock' => 30,
                'gender' => 'unisex',
                'short' => 'Full suede upper, metallic gold foil callout, and ultra-comfortable rubber midsole.',
                'desc' => "Since 1968, the Puma Suede has been worn by legends of track, b-boy crews, and contemporary minimalists alike.",
                'sizes' => ['39', '40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Puma Black / White', 'hex' => '#18181A'],
                    ['name' => 'Burgundy & Cream', 'hex' => '#6E262E'],
                ],
                'new' => false,
                'best' => true,
                'trending' => false,
                'sales' => 134,
            ],
            [
                'brand' => 'Puma',
                'name' => 'Puma RS-X Efekt Sculpted Tech Runner',
                'slug' => 'puma-rs-x-efekt',
                'sku' => 'SXO-PM-RSX',
                'style' => 'Lifestyle',
                'price' => 162000,
                'discount_price' => 145000,
                'stock' => 21,
                'gender' => 'unisex',
                'short' => 'Running System cushioning with angular suede/nubuck overlays and chunky sculpted heel.',
                'desc' => "Expressive multi-layered mesh and nubuck construction built atop Puma's signature RS polyurethane midsole.",
                'sizes' => ['40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Warm Alabaster & Terracotta', 'hex' => '#E4E0D8'],
                    ['name' => 'Obsidian & Volt', 'hex' => '#1F2937'],
                ],
                'new' => true,
                'best' => false,
                'trending' => true,
                'sales' => 109,
            ],
            [
                'brand' => 'Puma',
                'name' => 'Puma Palermo Vintage Terrace Leather & Gum',
                'slug' => 'puma-palermo-leather',
                'sku' => 'SXO-PM-PAL',
                'style' => 'Retro',
                'price' => 148000,
                'discount_price' => null,
                'stock' => 25,
                'gender' => 'unisex',
                'short' => '80s Italian coastal terrace archive sneaker with signature T-toe and gum outsole.',
                'desc' => "Straight from the Puma archives, the Palermo celebrates 1980s stadium terrace culture with supple leather and gold-lettered side tag.",
                'sizes' => ['38', '39', '40', '41', '42', '43', '44'],
                'colors' => [
                    ['name' => 'Cobalt Blue & Cream', 'hex' => '#204A80'],
                    ['name' => 'Terracotta & Gum', 'hex' => '#B85435'],
                ],
                'new' => true,
                'best' => true,
                'trending' => true,
                'sales' => 126,
            ],
            [
                'brand' => 'New Balance',
                'name' => 'New Balance 550 Vintage Basketball Oxford',
                'slug' => 'new-balance-550',
                'sku' => 'SXO-NB-550',
                'style' => 'Basketball',
                'price' => 190000,
                'discount_price' => 172000,
                'stock' => 19,
                'gender' => 'unisex',
                'short' => '1989 low-top pro basketball silhouette in perforated white leather and forest green.',
                'desc' => "Clean, streamlined low-top profile with micro-perforated leather saddle, vintage oxidized midsole, and N applique.",
                'sizes' => ['39', '40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'White / Pine Green', 'hex' => '#225C3E'],
                    ['name' => 'White / Shadow Grey', 'hex' => '#6B7280'],
                ],
                'new' => false,
                'best' => true,
                'trending' => true,
                'sales' => 176,
            ],
            [
                'brand' => 'New Balance',
                'name' => 'New Balance 9060 Sea Salt & Raincloud ABZORB',
                'slug' => 'new-balance-9060',
                'sku' => 'SXO-NB-9060',
                'style' => 'Luxury',
                'price' => 245000,
                'discount_price' => 220000,
                'stock' => 16,
                'gender' => 'unisex',
                'short' => 'Exaggerated Y2K futurism with dual-density ABZORB and SBS wavy pod midsole.',
                'desc' => "Reinterpreting classic 99X elements with a warped, architectural aesthetic. Pigskin suede and breathable mesh upper in tonal neutral palettes.",
                'sizes' => ['39', '40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Sea Salt & Raincloud', 'hex' => '#D8D2C6'],
                    ['name' => 'Triple Black Castlerock', 'hex' => '#1F2022'],
                ],
                'new' => true,
                'best' => true,
                'trending' => true,
                'sales' => 198,
            ],
            [
                'brand' => 'Converse',
                'name' => 'Converse Chuck Taylor All Star 70 High Vintage Canvas',
                'slug' => 'converse-chuck-70-high',
                'sku' => 'SXO-CV-C70',
                'style' => 'High-top',
                'price' => 128000,
                'discount_price' => 115000,
                'stock' => 34,
                'gender' => 'unisex',
                'short' => '12oz heavyweight organic canvas with glossy egret rubber sidewalls and OrthoLite insole.',
                'desc' => "Crafted to 1970s specifications with winged tongue stitching, heavier grade canvas, and vintage license plate heel branding.",
                'sizes' => ['38', '39', '40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Black / Egret', 'hex' => '#161618'],
                    ['name' => 'Parchment Cream', 'hex' => '#EFECE6'],
                ],
                'new' => false,
                'best' => true,
                'trending' => false,
                'sales' => 142,
            ],
            [
                'brand' => 'Vans',
                'name' => 'Vans Old Skool 36 DX Anaheim Factory Suede',
                'slug' => 'vans-old-skool-36-dx',
                'sku' => 'SXO-VN-OS36',
                'style' => 'Low-top',
                'price' => 122000,
                'discount_price' => null,
                'stock' => 27,
                'gender' => 'unisex',
                'short' => 'Throwback 1966 Anaheim details with heavy suede toe cap, leather sidestripe, and waffle sole.',
                'desc' => "Paying tribute to the first Vans factory in Anaheim, California, upgraded with UltraCush sockliners and higher-gloss foxing tape.",
                'sizes' => ['39', '40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Black / True White', 'hex' => '#18181C'],
                    ['name' => 'Checkerboard Cream', 'hex' => '#E5E0D5'],
                ],
                'new' => false,
                'best' => false,
                'trending' => true,
                'sales' => 96,
            ],
            [
                'brand' => 'Reebok',
                'name' => 'Reebok Classic Leather 1983 Vintage Chalk',
                'slug' => 'reebok-classic-leather',
                'sku' => 'SXO-RB-CL83',
                'style' => 'Retro',
                'price' => 132000,
                'discount_price' => 118000,
                'stock' => 23,
                'gender' => 'unisex',
                'short' => 'Butter-soft garment leather upper, terry cloth lining, and Union Jack archival window box.',
                'desc' => "Clean British athletic minimalism with die-cut EVA midsole and high-abrasion gum rubber outsole.",
                'sizes' => ['39', '40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Vintage Chalk & Vector Red', 'hex' => '#F4F1EA'],
                    ['name' => 'Core Black', 'hex' => '#181818'],
                ],
                'new' => false,
                'best' => false,
                'trending' => false,
                'sales' => 84,
            ],
            [
                'brand' => 'Asics',
                'name' => 'Asics Gel-Kayano 14 Cream & Metallic Silver',
                'slug' => 'asics-gel-kayano-14',
                'sku' => 'SXO-AS-GK14',
                'style' => 'Running',
                'price' => 235000,
                'discount_price' => 210000,
                'stock' => 15,
                'gender' => 'unisex',
                'short' => 'Late-2000s technical runner with TRUSSTIC stability system and visible GEL cushioning.',
                'desc' => "One of the most coveted silhouettes in contemporary fashion, combining breathable open mesh with metallic synthetic leather overlays.",
                'sizes' => ['39', '40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Cream / Metallic Champagne', 'hex' => '#E8E4DA'],
                    ['name' => 'Black / Pure Silver', 'hex' => '#26262A'],
                ],
                'new' => true,
                'best' => true,
                'trending' => true,
                'sales' => 168,
            ],
            [
                'brand' => 'Salomon',
                'name' => 'Salomon XT-6 Expanse GORE-TEX Trail Technical',
                'slug' => 'salomon-xt-6-expanse',
                'sku' => 'SXO-SL-XT6',
                'style' => 'Training',
                'price' => 260000,
                'discount_price' => null,
                'stock' => 14,
                'gender' => 'unisex',
                'short' => 'Agile Chassis System, Mud Contagrip outsole, and SensiFit gorpcore engineering.',
                'desc' => "Trusted by ultra-distance athletes and fashion editors alike for its unmistakable technical profile and all-weather durability.",
                'sizes' => ['40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Beluga Black & Burnt Ochre', 'hex' => '#262826'],
                    ['name' => 'Vanilla Ice & Lunar Rock', 'hex' => '#EAE6DD'],
                ],
                'new' => true,
                'best' => false,
                'trending' => true,
                'sales' => 112,
            ],
            [
                'brand' => 'On Running',
                'name' => 'On Running Cloudmonster Hyper Helion Superfoam',
                'slug' => 'on-cloudmonster-hyper',
                'sku' => 'SXO-ON-CMH',
                'style' => 'Running',
                'price' => 255000,
                'discount_price' => 232000,
                'stock' => 17,
                'gender' => 'unisex',
                'short' => 'Max-cushioned Swiss CloudTec pods with dual-foam Helion HF forefoot propulsion.',
                'desc' => "Engineered in Zurich for maximum cushioning and effortless forward rolling momentum on asphalt and studio floors.",
                'sizes' => ['40', '41', '42', '43', '44', '45'],
                'colors' => [
                    ['name' => 'Undyed Cream & Flame', 'hex' => '#F2F0EC'],
                    ['name' => 'All Black Eclipse', 'hex' => '#18181A'],
                ],
                'new' => true,
                'best' => true,
                'trending' => true,
                'sales' => 139,
            ],
        ];

        $sneakerAngleDefs = [
            ['suffix' => 'lateral', 'angle' => 'Lateral Side Profile', 'primary' => true],
            ['suffix' => 'medial', 'angle' => 'Medial & Arch View', 'primary' => false],
            ['suffix' => 'heel', 'angle' => 'Heel & Outsole Architecture', 'primary' => false],
            ['suffix' => 'detail', 'angle' => 'Upper Material & Stitch Close-Up', 'primary' => false],
            ['suffix' => 'onfoot', 'angle' => 'Editorial On-Foot Lookbook', 'primary' => false],
        ];

        foreach ($sneakerProducts as $sp) {
            $brandModel = $brandsMap[$sp['brand']] ?? $savagexoBrand;
            $primaryImg = "/images/sneakers/{$sp['slug']}-lateral.jpg";

            $product = Product::updateOrCreate(
                ['slug' => $sp['slug']],
                [
                    'category_id' => $sneakerCategory->id,
                    'brand_id' => $brandModel->id,
                    'brand_name' => $brandModel->name,
                    'name' => $sp['name'],
                    'sku' => $sp['sku'],
                    'short_description' => $sp['short'],
                    'description' => $sp['desc'],
                    'price' => $sp['price'],
                    'discount_price' => $sp['discount_price'],
                    'stock_quantity' => $sp['stock'],
                    'gender' => $sp['gender'],
                    'sneaker_style' => $sp['style'],
                    'product_type' => 'sneaker',
                    'material' => 'Premium Leather, Suede & Technical Mesh Upper / Rubber Cupsole',
                    'care_instructions' => 'Spot clean with soft bristle brush and neutral sneaker solution.',
                    'featured_image' => $primaryImg,
                    'is_featured' => true,
                    'is_new_arrival' => $sp['new'],
                    'is_best_seller' => $sp['best'],
                    'is_trending' => $sp['trending'],
                    'is_active' => true,
                    'sales_count' => $sp['sales'],
                    'views_count' => $sp['sales'] * 6,
                    'meta_title' => $sp['name'] . ' | SAVAGEXO SNEAKER VAULT',
                    'meta_description' => $sp['short'],
                ]
            );

            $product->images()->delete();
            $primaryColor = $sp['colors'][0]['name'];
            $secondaryColor = $sp['colors'][1]['name'] ?? $primaryColor;

            foreach ($sneakerAngleDefs as $idx => $aDef) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => "/images/sneakers/{$sp['slug']}-{$aDef['suffix']}.jpg",
                    'alt_text' => $product->name . ' — ' . $aDef['angle'],
                    'view_angle' => $aDef['angle'],
                    'color_name' => $idx === 4 ? $secondaryColor : $primaryColor,
                    'is_primary' => $aDef['primary'],
                    'sort_order' => $idx + 1,
                ]);
            }

            $product->allVariants()->delete();
            foreach ($sp['sizes'] as $size) {
                foreach ($sp['colors'] as $c) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $c['name'],
                        'color_hex' => $c['hex'],
                        'sku_variant' => $product->sku . '-' . $size . '-' . strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $c['name']), 0, 3)),
                        'stock_quantity' => 8,
                        'price_adjustment' => 0,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
