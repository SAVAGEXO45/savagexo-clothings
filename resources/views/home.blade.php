<x-layouts.app>
    @php
        $hero = $heroBanners->first();
        $promo = $promoBanners->first();
    @endphp

    <!-- 1. EDITORIAL HERO CAMPAIGN -->
    <section class="relative bg-[#0E0E0E] text-[#F7F5F0] border-b border-[#E2DDD3] overflow-hidden">
        <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-12 min-h-[78vh]">
            <!-- Left: Editorial Typography & Manifesto -->
            <div class="lg:col-span-6 p-8 sm:p-14 lg:p-20 flex flex-col justify-between z-10 bg-gradient-to-b from-[#0E0E0E] via-[#0E0E0E] to-[#161412]">
                <div class="space-y-6 my-auto">
                    <div class="inline-flex items-center gap-3 border border-[#C9A96E]/40 bg-white/5 px-3.5 py-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#B85435]"></span>
                        <span class="font-mono-tech text-[10px] uppercase tracking-[0.26em] text-[#C9A96E]">
                            {{ $hero?->eyebrow ?? 'COLLECTION 04 — LAGOS TO MILAN' }}
                        </span>
                    </div>

                    <h1 class="font-editorial text-4xl sm:text-6xl xl:text-7xl font-bold leading-[1.02] tracking-tight text-white">
                        {{ $hero?->title ?? 'ARCHITECTURAL SILHOUETTES & GLOBAL SNEAKER VAULT' }}
                    </h1>

                    <p class="text-sm sm:text-base text-neutral-300 max-w-xl leading-relaxed">
                        {{ $hero?->description ?? 'Uncompromising craftsmanship merging Nigerian sartorial heritage, 600GSM luxury streetwear, and the world’s most coveted sneaker icons.' }}
                    </p>

                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <a
                            href="{{ route('shop.index') }}"
                            class="bg-[#B85435] text-white hover:bg-white hover:text-[#0E0E0E] px-8 py-4 text-xs font-bold uppercase tracking-[0.22em] transition"
                        >
                            Shop Clothing Archive →
                        </a>
                        <a
                            href="{{ route('shop.sneakers') }}"
                            class="border border-white/40 text-white hover:border-[#C9A96E] hover:text-[#C9A96E] px-7 py-4 text-xs font-semibold uppercase tracking-[0.2em] transition"
                        >
                            Enter Sneaker Vault
                        </a>
                    </div>
                </div>

                <!-- Bottom Campaign Coordinates -->
                <div class="pt-10 mt-10 border-t border-white/10 grid grid-cols-3 gap-6 text-xs">
                    <div>
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-400">ATELIER ORIGIN</p>
                        <p class="font-semibold text-white mt-0.5">Victoria Island, Lagos</p>
                    </div>
                    <div>
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-400">SNEAKER BRANDS</p>
                        <p class="font-semibold text-white mt-0.5">Nike • Adidas • Puma • NB • Jordan</p>
                    </div>
                    <div>
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-400">DIRECT CONCIERGE</p>
                        <a href="mailto:SAVAGEXO@GMAIL.COM" class="font-mono-tech font-semibold text-[#C9A96E] hover:underline mt-0.5 block">SAVAGEXO@GMAIL.COM</a>
                    </div>
                </div>
            </div>

            <!-- Right: Full-Height Editorial Photography -->
            <div class="lg:col-span-6 relative min-h-[460px] lg:min-h-full bg-[#1C1C1C] overflow-hidden">
                <img
                    src="{{ $hero?->image_url ?? '/images/banners/hero-main.jpg' }}"
                    alt="{{ $hero?->title ?? 'SAVAGEXO Campaign' }}"
                    class="w-full h-full object-cover object-center"
                >
                <div class="absolute bottom-6 right-6 bg-[#0E0E0E]/90 backdrop-blur-xs border border-white/15 px-5 py-3.5 max-w-xs">
                    <p class="font-mono-tech text-[10px] uppercase tracking-[0.22em] text-[#C9A96E]">SEASON ARCHIVE // 36+ PIECES</p>
                    <p class="text-xs text-white font-medium mt-1">Multi-Angle Couture & Curated Global Footwear</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SNEAKER BRAND DIRECTORY BAR -->
    <section class="bg-[#EFECE6] border-b border-[#E2DDD3] py-6">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8">
            <div class="flex items-center justify-between gap-4 mb-4">
                <span class="font-mono-tech text-[11px] uppercase tracking-[0.24em] text-[#B85435] font-bold">
                    BROWSE SNEAKERS BY BRAND
                </span>
                <a href="{{ route('shop.sneakers') }}" class="font-mono-tech text-[11px] uppercase tracking-widest hover:text-[#B85435]">
                    All 15+ Brands →
                </a>
            </div>
            <div class="flex flex-wrap gap-2.5">
                @foreach($sneakerBrands as $sBrand)
                    <a
                        href="{{ route('shop.sneakers', ['brand' => $sBrand->slug]) }}"
                        class="px-4 py-2 bg-white border border-[#0E0E0E]/20 hover:border-[#0E0E0E] hover:bg-[#0E0E0E] hover:text-white text-xs font-bold uppercase tracking-[0.15em] transition"
                    >
                        {{ $sBrand->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 3. DEDICATED SNEAKER HERO BANNER & NEW SNEAKER ARRIVALS -->
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-16 space-y-14">
        <div class="bg-[#0E0E0E] text-[#F7F5F0] border border-[#0E0E0E] grid grid-cols-1 lg:grid-cols-12 overflow-hidden">
            <div class="lg:col-span-7 p-8 sm:p-14 flex flex-col justify-center space-y-5">
                <div class="inline-flex items-center gap-2">
                    <span class="px-2.5 py-1 bg-[#B85435] text-white font-mono-tech text-[10px] uppercase tracking-[0.22em] font-bold">
                        {{ $sneakerHero?->badge_text ?? 'SNEAKER DESTINATION' }}
                    </span>
                    <span class="font-mono-tech text-xs text-[#C9A96E] uppercase tracking-widest">
                        {{ $sneakerHero?->eyebrow ?? 'RUNNING • BASKETBALL • RETRO • STREETWEAR' }}
                    </span>
                </div>

                <h2 class="font-editorial text-3xl sm:text-5xl font-bold text-white leading-tight">
                    {{ $sneakerHero?->title ?? 'THE GLOBAL SNEAKER ARCHIVE — NIKE, ADIDAS, PUMA & NEW BALANCE' }}
                </h2>

                <p class="text-sm text-neutral-300 leading-relaxed max-w-2xl">
                    {{ $sneakerHero?->description ?? 'Explore iconic court, track, and lifestyle silhouettes with multi-angle studio views, EU 38–46 sizing, and instant Naira checkout.' }}
                </p>

                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a href="{{ route('shop.sneakers') }}" class="bg-[#B85435] text-white hover:bg-white hover:text-[#0E0E0E] px-7 py-4 text-xs font-bold uppercase tracking-[0.2em] transition">
                        Explore Sneaker Vault →
                    </a>
                    <a href="{{ route('shop.sneakers', ['style' => 'Running']) }}" class="border border-white/30 text-white hover:border-[#C9A96E] hover:text-[#C9A96E] px-6 py-4 text-xs font-bold uppercase tracking-[0.18em] transition">
                        Running Edit
                    </a>
                    <a href="{{ route('shop.sneakers', ['style' => 'Basketball']) }}" class="border border-white/30 text-white hover:border-[#C9A96E] hover:text-[#C9A96E] px-6 py-4 text-xs font-bold uppercase tracking-[0.18em] transition">
                        Basketball Edit
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 bg-[#EFECE6] min-h-[360px] flex items-center justify-center overflow-hidden">
                <img
                    src="{{ $sneakerHero?->image_url ?? '/images/sneakers/air-jordan-1-retro-high-og-lateral.jpg' }}"
                    alt="SAVAGEXO Sneaker Vault"
                    loading="lazy"
                    class="w-full h-full object-cover"
                >
            </div>
        </div>

        <!-- New Sneaker Arrivals -->
        <div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-8 border-b border-[#E2DDD3]">
                <div>
                    <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">FRESH FOOTWEAR DROPS</p>
                    <h2 class="font-editorial text-3xl sm:text-5xl font-bold mt-1">New Sneaker Arrivals</h2>
                </div>
                <a href="{{ route('shop.sneakers', ['new_arrivals' => 1]) }}" class="text-xs font-bold uppercase tracking-[0.2em] hover:text-[#B85435]">
                    View All Sneaker Arrivals →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                @foreach($newSneakerArrivals as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. BRAND SPOTLIGHT SECTIONS: NIKE, ADIDAS, PUMA & NEW BALANCE -->
    <section class="bg-[#EFECE6] border-y border-[#E2DDD3] py-20">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 space-y-20">
            <!-- Nike & Jordan Collection -->
            <div>
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-8 border-b border-[#D5CFC2]">
                    <div>
                        <span class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">SWOOSH & JUMPMAN ARCHIVE</span>
                        <h2 class="font-editorial text-3xl sm:text-5xl font-bold mt-1">Nike & Air Jordan Collection</h2>
                    </div>
                    <a href="{{ route('shop.sneakers', ['brand' => 'nike']) }}" class="btn-savage-outline px-6 py-3 text-xs font-bold uppercase tracking-[0.18em]">
                        Shop All Nike →
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                    @foreach($nikeCollection as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>

            <!-- Adidas Collection -->
            <div>
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-8 border-b border-[#D5CFC2]">
                    <div>
                        <span class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">THREE-STRIPES TERRACE & BOOST</span>
                        <h2 class="font-editorial text-3xl sm:text-5xl font-bold mt-1">Adidas Originals Collection</h2>
                    </div>
                    <a href="{{ route('shop.sneakers', ['brand' => 'adidas']) }}" class="btn-savage-outline px-6 py-3 text-xs font-bold uppercase tracking-[0.18em]">
                        Shop All Adidas →
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                    @foreach($adidasCollection as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>

            <!-- Puma & New Balance Collection -->
            <div>
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-8 border-b border-[#D5CFC2]">
                    <div>
                        <span class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">FORMSTRIP & SCULPTED RUNNERS</span>
                        <h2 class="font-editorial text-3xl sm:text-5xl font-bold mt-1">Puma & New Balance Collection</h2>
                    </div>
                    <a href="{{ route('shop.sneakers', ['brand' => 'puma']) }}" class="btn-savage-outline px-6 py-3 text-xs font-bold uppercase tracking-[0.18em]">
                        Explore Puma & NB →
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                    @foreach($pumaCollection as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- 5. FEATURED CLOTHING COLLECTIONS -->
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-20">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-10 border-b border-[#E2DDD3]">
            <div>
                <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">CURATED DEPARTMENTS</p>
                <h2 class="font-editorial text-3xl sm:text-5xl font-bold mt-2">Featured Clothing & Footwear Collections</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-bold uppercase tracking-[0.2em] hover:text-[#B85435] transition">
                View All {{ $categories->count() }} Categories →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
            @foreach($featuredCollections as $collection)
                <a
                    href="{{ route('shop.index', ['category' => $collection->slug]) }}"
                    class="group relative aspect-[3/4] bg-[#0E0E0E] overflow-hidden border border-[#0E0E0E] flex flex-col justify-end p-6"
                >
                    <img
                        src="{{ $collection->image }}"
                        alt="{{ $collection->name }}"
                        loading="lazy"
                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 group-hover:opacity-95 transition duration-700"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>

                    <div class="relative z-10 space-y-2">
                        <span class="font-mono-tech text-[10px] uppercase tracking-[0.22em] text-[#C9A96E]">
                            {{ $collection->active_products_count }} PIECES
                        </span>
                        <h3 class="font-editorial text-2xl font-bold text-white group-hover:text-[#C9A96E] transition">
                            {{ $collection->name }}
                        </h3>
                        <p class="text-xs text-neutral-300 line-clamp-2">
                            {{ $collection->description }}
                        </p>
                        <span class="inline-block pt-2 text-[11px] font-bold uppercase tracking-[0.2em] text-white border-b border-[#B85435]">
                            Enter Archive →
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- 6. TRENDING STREETWEAR, MEN'S FASHION, WOMEN'S FASHION & BEST-SELLING CLOTHING -->
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-8 space-y-20">
        <!-- Trending Streetwear -->
        <div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-8 border-b border-[#E2DDD3]">
                <div>
                    <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">600GSM & STREET SILHOUETTES</p>
                    <h2 class="font-editorial text-3xl sm:text-5xl font-bold mt-2">Trending Streetwear</h2>
                </div>
                <a href="{{ route('shop.index', ['category' => 'streetwear']) }}" class="text-xs font-bold uppercase tracking-[0.2em] hover:text-[#B85435]">
                    Shop Streetwear →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                @foreach($trendingStreetwear as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>

        <!-- Men's Fashion -->
        <div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-8 border-b border-[#E2DDD3]">
                <div>
                    <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">SARTORIAL TAILORING & ROYALTY</p>
                    <h2 class="font-editorial text-3xl sm:text-5xl font-bold mt-2">Men's Fashion</h2>
                </div>
                <a href="{{ route('shop.index', ['gender' => 'men']) }}" class="text-xs font-bold uppercase tracking-[0.2em] hover:text-[#B85435]">
                    Shop All Men's →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                @foreach($mensCollection as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>

        <!-- Women's Fashion -->
        <div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-8 border-b border-[#E2DDD3]">
                <div>
                    <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">SILK DRAPERY & EVENING COUTURE</p>
                    <h2 class="font-editorial text-3xl sm:text-5xl font-bold mt-2">Women's Fashion</h2>
                </div>
                <a href="{{ route('shop.index', ['gender' => 'women']) }}" class="text-xs font-bold uppercase tracking-[0.2em] hover:text-[#B85435]">
                    Shop All Women's →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                @foreach($womensCollection as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>

        <!-- Best-Selling Clothing -->
        <div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-8 border-b border-[#E2DDD3]">
                <div>
                    <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">MOST COVETED SIGNATURES</p>
                    <h2 class="font-editorial text-3xl sm:text-5xl font-bold mt-2">Best-Selling Clothing</h2>
                </div>
                <a href="{{ route('shop.index', ['best_sellers' => 1]) }}" class="text-xs font-bold uppercase tracking-[0.2em] hover:text-[#B85435]">
                    View All Best Sellers →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                @foreach($bestSellers as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 7. PROMOTIONAL EDITORIAL BANNER (TRADITIONAL ROYALTY) -->
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-20">
        <div class="bg-[#0E0E0E] text-[#F7F5F0] grid grid-cols-1 lg:grid-cols-12 border border-[#0E0E0E] overflow-hidden">
            <div class="lg:col-span-6 min-h-[380px] relative overflow-hidden">
                <img
                    src="{{ $promo?->image_url ?? '/images/banners/promo-editorial.jpg' }}"
                    alt="{{ $promo?->title ?? 'Sovereign Agbada Edit' }}"
                    loading="lazy"
                    class="w-full h-full object-cover"
                >
            </div>
            <div class="lg:col-span-6 p-8 sm:p-14 flex flex-col justify-center space-y-6">
                <span class="font-mono-tech text-xs uppercase tracking-[0.26em] text-[#C9A96E]">
                    {{ $promo?->eyebrow ?? 'HERITAGE COUTURE' }}
                </span>
                <h2 class="font-editorial text-3xl sm:text-5xl font-bold text-white leading-tight">
                    {{ $promo?->title ?? 'THE SOVEREIGN AGBADA & BESPOKE ROYALTY EDIT' }}
                </h2>
                <p class="text-sm text-neutral-300 leading-relaxed">
                    {{ $promo?->description ?? 'Each Traditional Wear piece undergoes 48 hours of precision tonal embroidery and hand-finishing in our Victoria Island atelier.' }}
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a
                        href="{{ $promo?->cta_link ?? route('shop.index', ['category' => 'traditional-wear']) }}"
                        class="bg-[#B85435] text-white hover:bg-white hover:text-[#0E0E0E] px-7 py-4 text-xs font-bold uppercase tracking-[0.2em] transition"
                    >
                        {{ $promo?->cta_text ?? 'Discover Traditional Wear' }} →
                    </a>
                    <span class="font-mono-tech text-xs text-[#C9A96E]">CODE: LAGOSVIP (₦25,000 OFF)</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. CLIENT TESTIMONIALS -->
    <section class="bg-[#0E0E0E] text-[#F7F5F0] py-20">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8">
            <div class="max-w-2xl">
                <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#C9A96E]">PRIVATE CLIENT CHRONICLES</p>
                <h2 class="font-editorial text-3xl sm:text-5xl font-bold mt-2 text-white">Verified Patron Testimonials</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                @foreach($testimonials as $review)
                    <div class="border border-white/15 bg-white/[0.02] p-8 flex flex-col justify-between space-y-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-[#C9A96E] tracking-widest text-sm">
                                    {{ str_repeat('★', $review->rating) }}
                                </span>
                                @if($review->is_verified_purchase)
                                    <span class="font-mono-tech text-[10px] uppercase tracking-widest text-emerald-400">
                                        ✓ Verified Buyer
                                    </span>
                                @endif
                            </div>
                            <h3 class="font-editorial text-2xl font-bold text-white">
                                “{{ $review->title }}”
                            </h3>
                            <p class="text-xs text-neutral-300 leading-relaxed">
                                {{ $review->comment }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs">
                            <div>
                                <p class="font-bold text-white uppercase tracking-wider">{{ $review->user?->name ?? 'Private Client' }}</p>
                                <p class="text-[11px] text-neutral-400 mt-0.5">{{ $review->product?->name }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 9. NEWSLETTER SUBSCRIPTION & DIRECT SUPPORT -->
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 pt-20">
        <div class="bg-[#EFECE6] border border-[#0E0E0E] p-8 sm:p-16 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-7 space-y-3">
                <span class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">THE SAVAGEXO INNER CIRCLE</span>
                <h2 class="font-editorial text-3xl sm:text-5xl font-bold text-[#0E0E0E]">
                    Unlock Limited Sneaker Raffles, Runway Drops & 15% Off
                </h2>
                <p class="text-sm text-neutral-600 max-w-xl">
                    Receive early access to numbered capsule collections, sneaker restocks, and an instant 15% privilege code. For private client inquiries, email <a href="mailto:SAVAGEXO@GMAIL.COM" class="font-bold text-[#0E0E0E] underline">SAVAGEXO@GMAIL.COM</a>.
                </p>
            </div>
            <div class="lg:col-span-5">
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input
                        type="email"
                        name="email"
                        required
                        placeholder="Enter your email address..."
                        class="flex-1 bg-white border border-[#0E0E0E] px-4 py-4 text-sm focus:outline-none focus:border-[#B85435]"
                    >
                    <button type="submit" class="btn-savage-primary px-8 py-4 text-xs font-bold uppercase tracking-[0.2em]">
                        Join Circle
                    </button>
                </form>
                <p class="text-[11px] text-neutral-500 mt-3 font-mono-tech">
                    INSTANT PRIVILEGE CODE: <strong class="text-[#0E0E0E]">SAVAGE15</strong> | SUPPORT: <a href="mailto:SAVAGEXO@GMAIL.COM" class="underline text-[#0E0E0E]">SAVAGEXO@GMAIL.COM</a>
                </p>
            </div>
        </div>
    </section>
</x-layouts.app>
