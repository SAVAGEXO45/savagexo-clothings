<x-layouts.app :title="$product->name" :metaDescription="$product->short_description">
    @php
        $galleryItems = $product->images->isNotEmpty()
            ? $product->images->map(fn ($img) => [
                'url' => $img->url,
                'angle' => $img->view_angle ?: ($img->alt_text ?: 'Studio View'),
                'color' => $img->color_name ?: '',
            ])->values()->toArray()
            : [[
                'url' => $product->primary_image_url,
                'angle' => 'Primary Studio View',
                'color' => '',
            ]];

        $sizes = $product->available_sizes ?: ($product->product_type === 'sneaker' ? ['41', '42', '43', '44'] : ['M', 'L']);
        $colors = $product->available_colors ?: [['name' => 'Obsidian Black', 'hex' => '#111111']];
        $inWishlist = in_array($product->id, $globalWishlistIds ?? []);
    @endphp

    <!-- Breadcrumb -->
    <nav class="max-w-[1440px] mx-auto px-4 sm:px-8 pt-6 pb-4 text-xs font-mono-tech uppercase tracking-widest text-neutral-500 flex flex-wrap items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-[#0E0E0E]">Maison</a>
        <span>/</span>
        @if($product->product_type === 'sneaker')
            <a href="{{ route('shop.sneakers') }}" class="hover:text-[#0E0E0E]">Sneakers Vault</a>
        @else
            <a href="{{ route('shop.index') }}" class="hover:text-[#0E0E0E]">Archive</a>
        @endif
        @if($product->category)
            <span>/</span>
            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="hover:text-[#0E0E0E]">{{ $product->category->name }}</a>
        @endif
        @if($product->brand)
            <span>/</span>
            <a href="{{ route('shop.sneakers', ['brand' => $product->brand->slug]) }}" class="text-[#B85435] hover:underline">{{ $product->brand->name }}</a>
        @endif
        <span>/</span>
        <span class="text-[#0E0E0E] font-semibold truncate">{{ $product->name }}</span>
    </nav>

    <!-- Main Product Detail Showcase -->
    <section
        x-data="{
            gallery: @js($galleryItems),
            activeIndex: 0,
            isZoomed: false,
            lightboxOpen: false,
            zoomX: 50,
            zoomY: 50,
            selectedSize: '{{ addslashes($sizes[0]) }}',
            selectedColor: '{{ addslashes($colors[0]['name']) }}',
            quantity: 1,
            maxStock: {{ max(1, $product->stock_quantity) }},
            get activeItem() {
                return this.gallery[this.activeIndex] || this.gallery[0];
            },
            selectColor(colorName) {
                this.selectedColor = colorName;
                const matchIdx = this.gallery.findIndex(item => item.color && item.color.toLowerCase() === colorName.toLowerCase());
                if (matchIdx !== -1) {
                    this.activeIndex = matchIdx;
                }
            },
            handleMouseMove(e) {
                if (!this.isZoomed) return;
                const rect = e.currentTarget.getBoundingClientRect();
                this.zoomX = ((e.clientX - rect.left) / rect.width) * 100;
                this.zoomY = ((e.clientY - rect.top) / rect.height) * 100;
            }
        }"
        class="max-w-[1440px] mx-auto px-4 sm:px-8 py-6"
    >
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left: Multi-Image Interactive Gallery with Angle Captions & Zoom -->
            <div class="lg:col-span-7 flex flex-col-reverse sm:flex-row gap-4">
                <!-- Clickable Thumbnails Strip -->
                <div class="flex sm:flex-col gap-3 overflow-x-auto sm:w-28 flex-shrink-0 pb-2 sm:pb-0">
                    <template x-for="(item, index) in gallery" :key="index">
                        <button
                            type="button"
                            @click="activeIndex = index"
                            class="group relative w-20 h-24 sm:w-28 sm:h-32 border transition-all duration-300 overflow-hidden bg-[#EFECE6] flex-shrink-0 text-left"
                            :class="activeIndex === index ? 'border-2 border-[#B85435] ring-1 ring-[#B85435]' : 'border-[#E2DDD3] opacity-75 hover:opacity-100 hover:border-[#0E0E0E]'"
                        >
                            <img :src="item.url" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                            <span
                                class="absolute bottom-0 inset-x-0 bg-[#0E0E0E]/85 text-white font-mono-tech text-[9px] uppercase tracking-wider px-1.5 py-1 truncate"
                                x-text="item.angle || ('View ' + (index + 1))"
                            ></span>
                        </button>
                    </template>
                </div>

                <!-- Large Main Product Image Frame with Interactive Zoom & Lightbox -->
                <div class="flex-1 flex flex-col gap-3">
                    <div
                        @mousemove="handleMouseMove($event)"
                        @mouseleave="isZoomed = false"
                        @click="isZoomed = !isZoomed"
                        class="relative bg-[#EFECE6] border border-[#E2DDD3] aspect-[4/5] overflow-hidden cursor-zoom-in select-none group"
                        :class="isZoomed ? 'cursor-zoom-out' : 'cursor-zoom-in'"
                    >
                        <img
                            :src="activeItem.url"
                            :alt="activeItem.angle || '{{ addslashes($product->name) }}'"
                            class="w-full h-full object-cover object-center transition-transform duration-300 ease-out"
                            :style="isZoomed ? `transform: scale(2.15); transform-origin: ${zoomX}% ${zoomY}%;` : 'transform: scale(1);'"
                        >

                        <!-- Top Badges -->
                        <div class="absolute top-4 left-4 flex flex-col gap-2 pointer-events-none">
                            @if($product->has_discount)
                                <span class="bg-[#B85435] text-white text-xs font-mono-tech uppercase tracking-[0.2em] px-3 py-1.5 font-bold">
                                    SAVE {{ $product->discount_percentage }}%
                                </span>
                            @endif
                            <span
                                class="bg-[#0E0E0E]/90 text-[#C9A96E] text-[10px] font-mono-tech uppercase tracking-[0.2em] px-3 py-1"
                                x-text="activeItem.angle + ' (' + (activeIndex + 1) + '/' + gallery.length + ')'"
                            ></span>
                        </div>

                        <!-- Zoom & Fullscreen Controls -->
                        <div class="absolute bottom-4 right-4 flex items-center gap-2">
                            <button
                                type="button"
                                @click.stop="isZoomed = !isZoomed"
                                class="bg-[#0E0E0E]/90 text-white hover:bg-[#B85435] px-3 py-2 text-[10px] font-mono-tech uppercase tracking-widest transition"
                            >
                                <span x-text="isZoomed ? 'Reset Zoom (1x)' : 'Hover / Click Zoom (2x)'"></span>
                            </button>
                            <button
                                type="button"
                                @click.stop="lightboxOpen = true"
                                class="bg-white/95 text-[#0E0E0E] hover:bg-[#0E0E0E] hover:text-white border border-[#0E0E0E] px-3 py-2 text-[10px] font-mono-tech uppercase tracking-widest transition"
                            >
                                Fullscreen ⤢
                            </button>
                        </div>

                        <!-- Prev / Next Arrows -->
                        <button
                            type="button"
                            @click.stop="activeIndex = (activeIndex - 1 + gallery.length) % gallery.length"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 border border-[#0E0E0E] flex items-center justify-center text-sm font-bold opacity-0 group-hover:opacity-100 transition hover:bg-[#0E0E0E] hover:text-white"
                            aria-label="Previous Angle"
                        >
                            ←
                        </button>
                        <button
                            type="button"
                            @click.stop="activeIndex = (activeIndex + 1) % gallery.length"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 border border-[#0E0E0E] flex items-center justify-center text-sm font-bold opacity-0 group-hover:opacity-100 transition hover:bg-[#0E0E0E] hover:text-white"
                            aria-label="Next Angle"
                        >
                            →
                        </button>
                    </div>

                    <!-- Gallery Angle Bar -->
                    <div class="flex items-center justify-between bg-[#EFECE6] border border-[#E2DDD3] px-4 py-2.5 text-xs">
                        <span class="font-mono-tech text-[11px] uppercase tracking-wider text-neutral-600">
                            ACTIVE VIEW: <strong class="text-[#0E0E0E]" x-text="activeItem.angle"></strong>
                        </span>
                        <span class="font-mono-tech text-[11px] text-[#B85435]">
                            {{ count($galleryItems) }} High-Resolution Studio Angles
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Sartorial / Sneaker Details & Add to Bag Form -->
            <div class="lg:col-span-5 flex flex-col justify-between space-y-8">
                <div class="space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-[#E2DDD3] pb-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 bg-[#0E0E0E] text-[#C9A96E] font-mono-tech text-[10px] uppercase tracking-[0.22em] font-bold">
                                {{ $product->display_brand }}
                            </span>
                            @if($product->sneaker_style)
                                <span class="px-2.5 py-1 bg-[#EFECE6] border border-[#D5CFC2] text-[#0E0E0E] font-mono-tech text-[10px] uppercase tracking-widest">
                                    {{ $product->sneaker_style }}
                                </span>
                            @endif
                            <span class="font-mono-tech text-xs uppercase tracking-[0.2em] text-[#B85435]">
                                SKU: {{ $product->sku }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-mono-tech">
                            <span class="text-[#B85435]">★ {{ number_format($product->average_rating, 1) }}</span>
                            <span class="text-neutral-400">({{ $product->reviews->count() }} Reviews)</span>
                        </div>
                    </div>

                    <h1 class="font-editorial text-3xl sm:text-5xl font-bold text-[#0E0E0E] leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Price & Stock Badge -->
                    <div class="flex items-baseline justify-between gap-4">
                        <div class="flex items-baseline gap-3">
                            <span class="font-mono-tech text-2xl sm:text-3xl font-bold text-[#0E0E0E]">
                                {{ $product->formatted_effective_price }}
                            </span>
                            @if($product->has_discount)
                                <span class="font-mono-tech text-base text-neutral-400 line-through">
                                    {{ $product->formatted_price }}
                                </span>
                            @endif
                        </div>

                        @if($product->stock_quantity > 0)
                            <span class="inline-flex items-center gap-1.5 text-xs font-mono-tech uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                In Stock ({{ $product->stock_quantity }} Available)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-mono-tech uppercase tracking-wider text-red-700 bg-red-50 border border-red-200 px-3 py-1">
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    @if($product->short_description)
                        <p class="text-sm text-neutral-700 leading-relaxed">
                            {{ $product->short_description }}
                        </p>
                    @endif

                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-6 pt-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="size" :value="selectedSize">
                        <input type="hidden" name="color" :value="selectedColor">

                        <!-- Colorway Selection (Switches Gallery Image When Matched) -->
                        <div>
                            <div class="flex items-center justify-between text-xs uppercase tracking-widest mb-2.5">
                                <span class="font-semibold">Colourway: <strong class="font-mono-tech text-[#B85435]" x-text="selectedColor"></strong></span>
                                <span class="font-mono-tech text-[10px] text-neutral-500">Click swatch to preview colour</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                @foreach($colors as $clr)
                                    <button
                                        type="button"
                                        @click="selectColor('{{ addslashes($clr['name']) }}')"
                                        class="flex items-center gap-2 px-3.5 py-2 border text-xs transition"
                                        :class="selectedColor === '{{ addslashes($clr['name']) }}' ? 'border-[#0E0E0E] bg-[#0E0E0E] text-white font-semibold' : 'border-[#E2DDD3] bg-white text-[#0E0E0E] hover:border-[#0E0E0E]'"
                                    >
                                        <span class="w-3.5 h-3.5 rounded-full border border-white/40" style="background-color: {{ $clr['hex'] }}"></span>
                                        <span>{{ $clr['name'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Size Selection -->
                        <div>
                            <div class="flex items-center justify-between text-xs uppercase tracking-widest mb-2.5">
                                <span class="font-semibold">
                                    {{ $product->product_type === 'sneaker' ? 'Select Shoe Size (EU):' : 'Select Size:' }}
                                    <strong class="font-mono-tech text-[#B85435]" x-text="selectedSize"></strong>
                                </span>
                                <span class="font-mono-tech text-[11px] text-neutral-500">
                                    {{ $product->product_type === 'sneaker' ? 'Standard EU Sneaker Sizing' : 'True to SAVAGEXO Fit' }}
                                </span>
                            </div>
                            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2.5">
                                @foreach($sizes as $sz)
                                    <button
                                        type="button"
                                        @click="selectedSize = '{{ addslashes($sz) }}'"
                                        class="py-3 text-xs font-mono-tech uppercase border transition text-center"
                                        :class="selectedSize === '{{ addslashes($sz) }}' ? 'border-[#0E0E0E] bg-[#0E0E0E] text-white font-bold' : 'border-[#E2DDD3] bg-white text-[#0E0E0E] hover:border-[#0E0E0E]'"
                                    >
                                        {{ $sz }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Quantity & Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <!-- Quantity Stepper -->
                            <div class="flex items-center border border-[#0E0E0E] bg-white h-14 sm:w-36">
                                <button
                                    type="button"
                                    @click="quantity = Math.max(1, quantity - 1)"
                                    class="w-11 h-full flex items-center justify-center text-lg hover:bg-[#EFECE6]"
                                >−</button>
                                <input
                                    type="number"
                                    name="quantity"
                                    x-model="quantity"
                                    min="1"
                                    :max="maxStock"
                                    class="w-full text-center font-mono-tech text-sm font-bold focus:outline-none"
                                >
                                <button
                                    type="button"
                                    @click="quantity = Math.min(maxStock, quantity + 1)"
                                    class="w-11 h-full flex items-center justify-center text-lg hover:bg-[#EFECE6]"
                                >+</button>
                            </div>

                            <!-- Add to Bag Submit -->
                            @if($product->stock_quantity > 0)
                                <button
                                    type="submit"
                                    class="flex-1 h-14 btn-savage-primary text-xs font-bold uppercase tracking-[0.22em]"
                                >
                                    Add to Shopping Bag — {{ $product->formatted_effective_price }}
                                </button>
                            @else
                                <button
                                    type="button"
                                    disabled
                                    class="flex-1 h-14 bg-neutral-300 text-neutral-600 text-xs font-bold uppercase tracking-[0.22em] cursor-not-allowed"
                                >
                                    Currently Sold Out
                                </button>
                            @endif

                            <!-- Wishlist Button -->
                            <button
                                type="button"
                                onclick="window.savageToggleWishlist({{ $product->id }}, this)"
                                class="h-14 px-5 border border-[#0E0E0E] flex items-center justify-center hover:bg-[#0E0E0E] hover:text-white transition {{ $inWishlist ? 'bg-[#0E0E0E] text-[#B85435]' : 'bg-white text-[#0E0E0E]' }}"
                                title="Save to Wishlist"
                            >
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Craftsmanship & Care Accordion -->
                    <div class="border-t border-[#E2DDD3] pt-6 space-y-5 text-xs leading-relaxed">
                        <div>
                            <h3 class="font-bold uppercase tracking-[0.18em] text-[#0E0E0E] mb-1.5">Product Description & Specifications</h3>
                            <p class="text-neutral-600 whitespace-pre-line">{{ $product->description }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="p-4 bg-[#EFECE6] border border-[#E2DDD3]">
                                <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">MATERIALS & CONSTRUCTION</p>
                                <p class="font-semibold text-[#0E0E0E] mt-1">{{ $product->material ?? '100% Luxury Heavyweight Cotton & Silk' }}</p>
                            </div>
                            <div class="p-4 bg-[#EFECE6] border border-[#E2DDD3]">
                                <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">CARE & CONCIERGE</p>
                                <p class="font-semibold text-[#0E0E0E] mt-1">{{ $product->care_instructions ?? 'Specialist care recommended.' }}</p>
                            </div>
                        </div>

                        <div class="p-4 bg-[#0E0E0E] text-[#F7F5F0] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <span class="font-mono-tech text-[11px] uppercase tracking-wider text-[#C9A96E]">
                                NEED SIZING OR ORDER ASSISTANCE?
                            </span>
                            <a href="mailto:SAVAGEXO@GMAIL.COM" class="font-mono-tech text-xs font-bold text-white underline hover:text-[#C9A96E]">
                                SAVAGEXO@GMAIL.COM
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fullscreen Lightbox Modal -->
        <div
            x-show="lightboxOpen"
            x-cloak
            @keydown.escape.window="lightboxOpen = false"
            class="fixed inset-0 z-50 bg-black/90 flex flex-col items-center justify-center p-4 sm:p-10"
        >
            <div class="w-full max-w-4xl flex items-center justify-between text-white mb-4">
                <div>
                    <p class="font-mono-tech text-xs uppercase tracking-widest text-[#C9A96E]" x-text="activeItem.angle"></p>
                    <h3 class="font-editorial text-2xl font-bold">{{ $product->name }}</h3>
                </div>
                <button
                    type="button"
                    @click="lightboxOpen = false"
                    class="px-4 py-2 border border-white/30 font-mono-tech text-xs uppercase tracking-widest hover:bg-white hover:text-black"
                >
                    Close ✕
                </button>
            </div>
            <div class="relative max-w-3xl w-full max-h-[75vh] flex items-center justify-center bg-[#141414] border border-white/15 overflow-hidden">
                <img :src="activeItem.url" alt="{{ $product->name }}" class="max-h-[72vh] w-auto object-contain">
            </div>
            <div class="flex items-center gap-3 mt-4 overflow-x-auto max-w-3xl">
                <template x-for="(item, index) in gallery" :key="'lb-' + index">
                    <button
                        type="button"
                        @click="activeIndex = index"
                        class="w-16 h-20 border overflow-hidden"
                        :class="activeIndex === index ? 'border-2 border-[#C9A96E]' : 'border-white/20 opacity-60'"
                    >
                        <img :src="item.url" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
        </div>
    </section>

    <!-- Client Reviews & Ratings Section -->
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-16 mt-12 border-t border-[#E2DDD3]">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left: Rating Summary & Write a Review -->
            <div class="lg:col-span-5 space-y-6">
                <div>
                    <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">PATRON FEEDBACK</p>
                    <h2 class="font-editorial text-3xl sm:text-4xl font-bold mt-1">Client Reviews & Fit Impressions</h2>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="font-mono-tech text-3xl font-bold">{{ number_format($product->average_rating, 1) }}</span>
                        <span class="text-[#B85435] text-lg">★★★★★</span>
                        <span class="text-xs text-neutral-500">Based on {{ $product->reviews->count() }} reviews</span>
                    </div>
                </div>

                @auth
                    <form action="{{ route('shop.reviews.store', $product) }}" method="POST" class="bg-[#EFECE6] border border-[#0E0E0E] p-6 space-y-4">
                        @csrf
                        <h3 class="text-xs font-bold uppercase tracking-[0.18em]">Submit Your Review</h3>

                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">Rating</label>
                            <select name="rating" class="w-full bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs">
                                <option value="5">★★★★★ (5/5 — Exceptional)</option>
                                <option value="4">★★★★☆ (4/5 — Very Good)</option>
                                <option value="3">★★★☆☆ (3/5 — Satisfactory)</option>
                                <option value="2">★★☆☆☆ (2/5 — Below Expectation)</option>
                                <option value="1">★☆☆☆☆ (1/5 — Poor)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">Headline</label>
                            <input
                                type="text"
                                name="title"
                                required
                                placeholder="e.g. Impeccable tailoring and fabric weight"
                                class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5 text-xs"
                            >
                        </div>

                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">Your Review</label>
                            <textarea
                                name="comment"
                                rows="3"
                                required
                                placeholder="Share your thoughts on sizing, comfort, and craftsmanship..."
                                class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5 text-xs"
                            ></textarea>
                        </div>

                        <button type="submit" class="btn-savage-primary w-full py-3 text-xs font-bold uppercase tracking-[0.2em]">
                            Publish Review
                        </button>
                    </form>
                @else
                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-6 space-y-3">
                        <p class="text-xs text-neutral-600">Sign in to your SAVAGEXO client profile to leave a verified review for this piece.</p>
                        <a href="{{ route('login') }}" class="inline-block btn-savage-outline px-5 py-2.5 text-xs font-bold uppercase tracking-widest">
                            Sign In to Review →
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Right: Published Reviews List -->
            <div class="lg:col-span-7 space-y-6">
                @forelse($product->reviews as $review)
                    <div class="bg-white border border-[#E2DDD3] p-6 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-[#B85435] text-sm tracking-widest">{{ str_repeat('★', $review->rating) }}</span>
                                <span class="text-xs font-bold uppercase tracking-wider">{{ $review->user?->name ?? 'SAVAGEXO Patron' }}</span>
                                @if($review->is_verified_purchase)
                                    <span class="font-mono-tech text-[10px] uppercase tracking-widest text-emerald-700 bg-emerald-50 px-2 py-0.5 border border-emerald-200">
                                        ✓ Verified Purchase
                                    </span>
                                @endif
                            </div>
                            <span class="font-mono-tech text-[11px] text-neutral-400">{{ $review->created_at->format('d M Y') }}</span>
                        </div>
                        <h4 class="font-editorial text-xl font-bold">{{ $review->title }}</h4>
                        <p class="text-xs text-neutral-600 leading-relaxed">{{ $review->comment }}</p>
                    </div>
                @empty
                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-10 text-center">
                        <p class="text-xs text-neutral-600">Be the first patron to review {{ $product->name }}.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-16 border-t border-[#E2DDD3]">
            <div class="flex items-end justify-between pb-8">
                <div>
                    <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">COMPLETE THE ROTATION</p>
                    <h2 class="font-editorial text-3xl sm:text-4xl font-bold mt-1">Complementary Pieces</h2>
                </div>
                <a href="{{ $product->product_type === 'sneaker' ? route('shop.sneakers') : route('shop.index') }}" class="text-xs font-bold uppercase tracking-[0.2em] hover:text-[#B85435]">
                    View Archive →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                    <x-product-card :product="$rel" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.app>
