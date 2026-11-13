<x-layouts.app :title="$activeCategory ? $activeCategory->name : ($activeBrand ? $activeBrand->name . ' Collection' : 'Shop All Collections')">
    <!-- Page Header -->
    <section class="bg-[#0E0E0E] text-[#F7F5F0] py-14 border-b border-[#E2DDD3]">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#C9A96E]">
                    SAVAGEXO ARCHIVE // {{ strtoupper($activeBrand?->name ?? request('gender', $activeCategory?->name ?? 'ALL CLOTHING & SNEAKERS')) }}
                </p>
                <h1 class="font-editorial text-4xl sm:text-6xl font-bold text-white mt-2">
                    @if($activeBrand)
                        {{ $activeBrand->name }} Collection
                    @elseif($activeCategory)
                        {{ $activeCategory->name }}
                    @elseif(request('gender') === 'men')
                        Men's Architectural Collection
                    @elseif(request('gender') === 'women')
                        Women's Couture & Silk Edit
                    @elseif(request('collection') === 'new-arrivals' || request()->boolean('new_arrivals'))
                        New Season Arrivals
                    @elseif(request('collection') === 'best-sellers' || request()->boolean('best_sellers'))
                        Best-Selling Signatures
                    @else
                        All Clothing & Footwear Collections
                    @endif
                </h1>
                @if($activeCategory?->description)
                    <p class="text-sm text-neutral-400 max-w-2xl mt-2">{{ $activeCategory->description }}</p>
                @elseif($activeBrand?->description)
                    <p class="text-sm text-neutral-400 max-w-2xl mt-2">{{ $activeBrand->description }}</p>
                @endif
            </div>
            <div class="font-mono-tech text-xs text-neutral-400">
                SHOWING <strong class="text-white">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong> OF <strong class="text-[#C9A96E]">{{ $products->total() }}</strong> PIECES
            </div>
        </div>
    </section>

    <!-- Main Shop Layout -->
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Sidebar: Multi-Faceted Filter Form -->
            <aside class="lg:col-span-3 space-y-8">
                <form action="{{ route('shop.index') }}" method="GET" class="bg-[#EFECE6] border border-[#E2DDD3] p-6 space-y-6">
                    <div class="flex items-center justify-between border-b border-[#D5CFC2] pb-3">
                        <h2 class="text-xs font-bold uppercase tracking-[0.2em]">Refine Archive</h2>
                        <a href="{{ route('shop.index') }}" class="font-mono-tech text-[11px] text-[#B85435] hover:underline">
                            Reset All
                        </a>
                    </div>

                    <!-- Search Keyword -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Search Keyword</label>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Agbada, Nike, Silk, Samba..."
                            class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#B85435]"
                        >
                    </div>

                    <!-- Brand Filter -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Brand</label>
                        <select name="brand" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">All Brands (SAVAGEXO + Sneakers)</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->slug }}" {{ request('brand') === $brand->slug ? 'selected' : '' }}>
                                    {{ $brand->name }} ({{ $brand->active_products_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department / Category -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2.5">Product Categories</label>
                        <div class="space-y-2 text-xs max-h-56 overflow-y-auto pr-1">
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="flex items-center gap-2">
                                    <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} class="accent-[#0E0E0E]">
                                    <span>All Categories</span>
                                </span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center justify-between cursor-pointer hover:text-[#B85435]">
                                    <span class="flex items-center gap-2">
                                        <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'checked' : '' }} class="accent-[#0E0E0E]">
                                        <span>{{ $cat->name }}</span>
                                    </span>
                                    <span class="font-mono-tech text-[10px] text-neutral-500">({{ $cat->active_products_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sneaker Style Filter -->
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

                    <!-- Gender -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Gender</label>
                        <select name="gender" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">All Genders</option>
                            <option value="men" {{ request('gender') === 'men' ? 'selected' : '' }}>Men's Edit</option>
                            <option value="women" {{ request('gender') === 'women' ? 'selected' : '' }}>Women's Edit</option>
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

                    <!-- Clothing Size Selection -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Clothing Size</label>
                        <select name="clothing_size" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">Any Clothing Size</option>
                            @foreach($clothingSizes as $csz)
                                <option value="{{ $csz }}" {{ request('clothing_size') === $csz ? 'selected' : '' }}>{{ $csz }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Shoe Size Selection -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Shoe Size (EU)</label>
                        <select name="shoe_size" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">Any Shoe Size</option>
                            @foreach($shoeSizes as $ssz)
                                <option value="{{ $ssz }}" {{ (string) request('shoe_size') === (string) $ssz ? 'selected' : '' }}>EU {{ $ssz }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Color Selection -->
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

                    <!-- Availability -->
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-widest text-neutral-600 mb-2">Availability</label>
                        <select name="availability" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                            <option value="">All Pieces</option>
                            <option value="in_stock" {{ request('availability') === 'in_stock' ? 'selected' : '' }}>In Stock Ready to Ship</option>
                            <option value="on_sale" {{ request('availability') === 'on_sale' ? 'selected' : '' }}>Discounted / On Sale</option>
                        </select>
                    </div>

                    <!-- New Arrivals & Best Sellers Checkboxes -->
                    <div class="space-y-2 pt-1 border-t border-[#D5CFC2] text-xs">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="new_arrivals" value="1" {{ request()->boolean('new_arrivals') || request('collection') === 'new-arrivals' ? 'checked' : '' }} class="accent-[#0E0E0E]">
                            <span class="font-semibold">New Arrivals Only</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="best_sellers" value="1" {{ request()->boolean('best_sellers') || request('collection') === 'best-sellers' ? 'checked' : '' }} class="accent-[#0E0E0E]">
                            <span class="font-semibold">Best Sellers Only</span>
                        </label>
                    </div>

                    <!-- Preserve Sort -->
                    <input type="hidden" name="sort" value="{{ $sort }}">

                    <button type="submit" class="w-full btn-savage-primary py-3.5 text-xs font-bold uppercase tracking-[0.2em]">
                        Apply Filters
                    </button>
                </form>
            </aside>

            <!-- Right Product Grid -->
            <div class="lg:col-span-9 space-y-8">
                <!-- Top Sorting Bar & Active Filter Pills -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-[#E2DDD3]">
                    <div class="flex flex-wrap items-center gap-2">
                        @if(request('q'))
                            <span class="bg-[#0E0E0E] text-white text-[11px] font-mono-tech px-3 py-1">Search: “{{ request('q') }}”</span>
                        @endif
                        @if(request('brand'))
                            <span class="bg-[#0E0E0E] text-[#C9A96E] text-[11px] font-mono-tech px-3 py-1 uppercase">Brand: {{ request('brand') }}</span>
                        @endif
                        @if(request('category'))
                            <span class="bg-[#0E0E0E] text-white text-[11px] font-mono-tech px-3 py-1">Category: {{ request('category') }}</span>
                        @endif
                        @if(request('style'))
                            <span class="bg-[#B85435] text-white text-[11px] font-mono-tech px-3 py-1">Style: {{ request('style') }}</span>
                        @endif
                        @if(request('clothing_size'))
                            <span class="bg-[#0E0E0E] text-white text-[11px] font-mono-tech px-3 py-1">Clothing Size: {{ request('clothing_size') }}</span>
                        @endif
                        @if(request('shoe_size'))
                            <span class="bg-[#0E0E0E] text-white text-[11px] font-mono-tech px-3 py-1">Shoe Size: EU {{ request('shoe_size') }}</span>
                        @endif
                        @if(request('size'))
                            <span class="bg-[#0E0E0E] text-white text-[11px] font-mono-tech px-3 py-1">Size: {{ request('size') }}</span>
                        @endif
                        @if(request('color'))
                            <span class="bg-[#0E0E0E] text-white text-[11px] font-mono-tech px-3 py-1">Colour: {{ request('color') }}</span>
                        @endif
                    </div>

                    <form action="{{ route('shop.index') }}" method="GET" class="flex items-center gap-3">
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
                        <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">NO ARCHIVE MATCHES</p>
                        <h3 class="font-editorial text-3xl font-bold">No pieces matched your current filter criteria.</h3>
                        <p class="text-xs text-neutral-600 max-w-md mx-auto">
                            Try clearing one or more filters or explore our full seasonal ready-to-wear and sneaker archive.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('shop.index') }}" class="inline-block btn-savage-primary px-8 py-3.5 text-xs font-bold uppercase tracking-[0.2em]">
                                View Full Collection
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>
