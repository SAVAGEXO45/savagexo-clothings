<x-layouts.app title="Sneakers Collection — Global Footwear Vault">
    <!-- Hero Header: SAVAGEXO Sneaker Vault -->
    <section class="relative bg-[#0E0E0E] text-[#F7F5F0] overflow-hidden border-b border-[#E2DDD3]">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 py-16 lg:py-20 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7 space-y-5">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="px-3 py-1 bg-[#B85435] text-white font-mono-tech text-[10px] uppercase tracking-[0.25em] font-bold">
                            SAVAGEXO SNEAKER VAULT
                        </span>
                        <span class="font-mono-tech text-xs uppercase tracking-[0.22em] text-[#C9A96E]">
                            {{ $products->total() }} SILHOUETTES AVAILABLE IN NAIRA (₦)
                        </span>
                    </div>
                    <h1 class="font-editorial text-4xl sm:text-6xl lg:text-7xl font-bold text-white leading-[0.95]">
                        @if($activeBrand)
                            {{ $activeBrand->name }} Footwear Archive
                        @elseif(request('style'))
                            {{ ucfirst(request('style')) }} Sneakers
                        @else
                            The Global Sneaker Destination.
                        @endif
                    </h1>
                    <p class="text-sm sm:text-base text-neutral-300 max-w-2xl leading-relaxed">
                        @if($activeBrand && $activeBrand->description)
                            {{ $activeBrand->description }}
                        @else
                            Explore coveted silhouettes from Nike, Adidas, Jordan, New Balance, Puma, Asics, Salomon, Converse, Vans, Reebok, and On Running — inspected and stocked for immediate dispatch across Lagos, Abuja, and nationwide.
                        @endif
                    </p>
                    <p class="font-mono-tech text-[11px] text-neutral-400">
                        * Curated by SAVAGEXO CLOTHINGS as an independent multi-brand retailer. All brand trademarks belong to their respective owners. Support: <a href="mailto:SAVAGEXO@GMAIL.COM" class="text-[#C9A96E] underline">SAVAGEXO@GMAIL.COM</a>
                    </p>
                </div>

                <!-- Right Featured Visual -->
                <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                    <div class="bg-[#181818] border border-white/15 p-3">
                        <img src="/images/sneakers/air-jordan-1-retro-high-og-lateral.jpg" alt="Air Jordan 1 High" class="w-full aspect-[4/5] object-cover">
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#C9A96E] mt-2">JORDAN // HIGH-TOP</p>
                    </div>
                    <div class="bg-[#181818] border border-white/15 p-3 mt-6">
                        <img src="/images/sneakers/new-balance-9060-lateral.jpg" alt="New Balance 9060" class="w-full aspect-[4/5] object-cover">
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#C9A96E] mt-2">NEW BALANCE // STREETWEAR</p>
                    </div>
                </div>
            </div>

            <!-- Interactive Brand Filter Strip -->
            <div class="mt-10 pt-8 border-t border-white/15">
                <div class="flex items-center justify-between mb-3">
                    <span class="font-mono-tech text-[11px] uppercase tracking-[0.22em] text-[#C9A96E]">BROWSE SNEAKERS BY BRAND</span>
                    @if(request('brand') || request('style'))
                        <a href="{{ route('shop.sneakers') }}" class="font-mono-tech text-[11px] uppercase text-[#B85435] hover:underline">
                            Clear Brand / Style Filter ✕
                        </a>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2">
                    <a
                        href="{{ route('shop.sneakers', array_merge(request()->except('brand', 'page'), [])) }}"
                        class="px-3.5 py-2 text-xs font-mono-tech uppercase tracking-wider border transition {{ !request('brand') ? 'bg-[#C9A96E] text-[#0E0E0E] border-[#C9A96E] font-bold' : 'border-white/20 text-neutral-300 hover:border-white' }}"
                    >
                        All Brands
                    </a>
                    @foreach($brands as $brand)
                        <a
                            href="{{ route('shop.sneakers', array_merge(request()->except('page'), ['brand' => $brand->slug])) }}"
                            class="px-3.5 py-2 text-xs font-mono-tech uppercase tracking-wider border transition {{ request('brand') === $brand->slug ? 'bg-[#C9A96E] text-[#0E0E0E] border-[#C9A96E] font-bold' : 'border-white/20 text-neutral-300 hover:border-[#C9A96E] hover:text-white' }}"
                        >
                            {{ $brand->name }}
                            @if($brand->active_products_count > 0)
                                <span class="text-[10px] opacity-75">({{ $brand->active_products_count }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Interactive Sneaker Style Filter Strip -->
            <div class="mt-6">
                <span class="block font-mono-tech text-[11px] uppercase tracking-[0.22em] text-neutral-400 mb-2.5">FILTER BY SNEAKER SILHOUETTE & STYLE</span>
                <div class="flex flex-wrap gap-2">
                    <a
                        href="{{ route('shop.sneakers', request()->except('style', 'page')) }}"
                        class="px-3 py-1.5 text-[11px] font-mono-tech uppercase tracking-wider border transition {{ !request('style') ? 'bg-[#B85435] text-white border-[#B85435] font-bold' : 'border-white/15 text-neutral-400 hover:text-white' }}"
                    >
                        All Styles
                    </a>
                    @foreach($sneakerStyles as $styleName)
                        <a
                            href="{{ route('shop.sneakers', array_merge(request()->except('page'), ['style' => $styleName])) }}"
                            class="px-3 py-1.5 text-[11px] font-mono-tech uppercase tracking-wider border transition {{ strtolower((string) request('style')) === strtolower($styleName) ? 'bg-[#B85435] text-white border-[#B85435] font-bold' : 'border-white/15 text-neutral-300 hover:border-white' }}"
                        >
                            {{ $styleName }} Sneakers
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Main Sneaker Shopping Grid & Sidebar Filters -->
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Sidebar Filters -->
            <aside class="lg:col-span-3 space-y-8">
                <form action="{{ route('shop.sneakers') }}" method="GET" class="bg-[#EFECE6] border border-[#E2DDD3] p-6 space-y-6">
                    <div class="flex items-center justify-between border-b border-[#D5CFC2] pb-3">
                        <h2 class="text-xs font-bold uppercase tracking-[0.2em]">Filter Sneakers</h2>
                        <a href="{{ route('shop.sneakers') }}" class="font-mono-tech text-[11px] text-[#B85435] hover:underline">
                            Reset All
                        </a>
                    </div>

                    <!-- Search -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Search Silhouette</label>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Air Force 1, Samba, 9060, Dunk..."
                            class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#B85435]"
                        >
                    </div>

                    <!-- Brand Dropdown -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Sneaker Brand</label>
                        <select name="brand" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">All Sneaker Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->slug }}" {{ request('brand') === $brand->slug ? 'selected' : '' }}>
                                    {{ $brand->name }} ({{ $brand->active_products_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sneaker Style Dropdown -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Sneaker Style</label>
                        <select name="style" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">All Styles</option>
                            @foreach($sneakerStyles as $st)
                                <option value="{{ $st }}" {{ strtolower((string) request('style')) === strtolower($st) ? 'selected' : '' }}>
                                    {{ $st }} Sneakers
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- EU Shoe Size -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Shoe Size (EU)</label>
                        <select name="shoe_size" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">Any Shoe Size</option>
                            @foreach($shoeSizes as $sz)
                                <option value="{{ $sz }}" {{ (string) request('shoe_size') === (string) $sz ? 'selected' : '' }}>
                                    EU {{ $sz }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Colorway -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Colourway</label>
                        <select name="color" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">Any Colour</option>
                            @foreach($availableColors as $clr)
                                <option value="{{ $clr->color }}" {{ request('color') === $clr->color ? 'selected' : '' }}>
                                    {{ $clr->color }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Gender Sizing</label>
                        <select name="gender" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">All Genders</option>
                            <option value="men" {{ request('gender') === 'men' ? 'selected' : '' }}>Men's Sizing</option>
                            <option value="women" {{ request('gender') === 'women' ? 'selected' : '' }}>Women's Sizing</option>
                            <option value="unisex" {{ request('gender') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                        </select>
                    </div>

                    <!-- Price Range (₦) -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Price Range (₦ NGN)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input
                                type="number"
                                name="min_price"
                                value="{{ request('min_price') }}"
                                placeholder="Min ₦"
                                class="w-full bg-white border border-[#0E0E0E] px-3 py-2 text-xs"
                            >
                            <input
                                type="number"
                                name="max_price"
                                value="{{ request('max_price') }}"
                                placeholder="Max ₦"
                                class="w-full bg-white border border-[#0E0E0E] px-3 py-2 text-xs"
                            >
                        </div>
                    </div>

                    <!-- Availability -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Availability</label>
                        <select name="availability" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">All Pairs</option>
                            <option value="in_stock" {{ request('availability') === 'in_stock' ? 'selected' : '' }}>In Stock Ready to Ship</option>
                            <option value="on_sale" {{ request('availability') === 'on_sale' ? 'selected' : '' }}>Discounted Pairs</option>
                        </select>
                    </div>

                    <!-- New Arrivals & Best Sellers Toggles -->
                    <div class="space-y-2 pt-1 border-t border-[#D5CFC2] text-xs">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="new_arrivals" value="1" {{ request()->boolean('new_arrivals') ? 'checked' : '' }} class="accent-[#0E0E0E]">
                            <span class="font-semibold">New Sneaker Arrivals Only</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="best_sellers" value="1" {{ request()->boolean('best_sellers') ? 'checked' : '' }} class="accent-[#0E0E0E]">
                            <span class="font-semibold">Best-Selling Grails Only</span>
                        </label>
                    </div>

                    <input type="hidden" name="sort" value="{{ $sort }}">

                    <button type="submit" class="w-full btn-savage-primary py-3.5 text-xs font-bold uppercase tracking-[0.2em]">
                        Apply Sneaker Filters
                    </button>
                </form>
            </aside>

            <!-- Right Sneaker Product Grid -->
            <div class="lg:col-span-9 space-y-8">
                <!-- Sorting & Active Filter Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-[#E2DDD3]">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-mono-tech text-xs text-neutral-500">
                            Showing <strong class="text-[#0E0E0E]">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong> of <strong class="text-[#B85435]">{{ $products->total() }}</strong> sneakers
                        </span>
                        @if(request('brand'))
                            <span class="bg-[#0E0E0E] text-[#C9A96E] text-[11px] font-mono-tech px-3 py-1 uppercase">Brand: {{ request('brand') }}</span>
                        @endif
                        @if(request('style'))
                            <span class="bg-[#B85435] text-white text-[11px] font-mono-tech px-3 py-1 uppercase">Style: {{ request('style') }}</span>
                        @endif
                        @if(request('shoe_size'))
                            <span class="bg-[#0E0E0E] text-white text-[11px] font-mono-tech px-3 py-1">EU {{ request('shoe_size') }}</span>
                        @endif
                    </div>

                    <form action="{{ route('shop.sneakers') }}" method="GET" class="flex items-center gap-3">
                        @foreach(request()->except('sort', 'page') as $k => $v)
                            @if(is_scalar($v))
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach
                        <label class="text-xs font-mono-tech uppercase tracking-wider text-neutral-500">Sort By:</label>
                        <select
                            name="sort"
                            onchange="this.form.submit()"
                            class="bg-white border border-[#0E0E0E] px-3.5 py-2 text-xs font-semibold uppercase tracking-wider"
                        >
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                            <option value="popularity" {{ $sort === 'popularity' ? 'selected' : '' }}>Most Popular</option>
                            <option value="best_selling" {{ $sort === 'best_selling' ? 'selected' : '' }}>Best Selling</option>
                            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High (₦)</option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low (₦)</option>
                        </select>
                    </form>
                </div>

                @if($products->isEmpty())
                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-16 text-center space-y-4">
                        <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">NO SNEAKERS MATCHED</p>
                        <h3 class="font-editorial text-3xl font-bold">No sneaker pairs matched your current filter selection.</h3>
                        <p class="text-xs text-neutral-600 max-w-md mx-auto">
                            Reset your brand or size filters to browse our complete sneaker vault, or contact our sneaker sourcing desk at <a href="mailto:SAVAGEXO@GMAIL.COM" class="underline font-bold">SAVAGEXO@GMAIL.COM</a>.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('shop.sneakers') }}" class="inline-block btn-savage-primary px-8 py-3.5 text-xs font-bold uppercase tracking-[0.2em]">
                                View All Sneakers
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="pt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>
