@props(['product'])

@php
    $inWishlist = in_array($product->id, $globalWishlistIds ?? []);
    $defaultSize = $product->available_sizes[0] ?? 'M';
    $defaultColor = $product->available_colors[0]['name'] ?? 'Obsidian Black';
    $galleryCount = $product->relationLoaded('images') ? $product->images->count() : $product->images()->count();
    $secondImage = $product->relationLoaded('images') && $product->images->count() > 1
        ? $product->images->get(1)->url
        : null;
@endphp

<article class="group relative flex flex-col bg-[#F7F5F0] border border-[#E2DDD3] hover:border-[#0E0E0E] transition-colors duration-300">
    <!-- Image Frame with Secondary Angle Hover Preview -->
    <div class="relative aspect-[4/5] bg-[#EFECE6] editorial-image-zoom overflow-hidden">
        <a href="{{ route('shop.show', $product->slug) }}" class="block w-full h-full relative">
            <img
                src="{{ $product->primary_image_url }}"
                alt="{{ $product->name }}"
                loading="lazy"
                class="w-full h-full object-cover object-center transition-opacity duration-500 {{ $secondImage ? 'group-hover:opacity-0' : '' }}"
            >
            @if($secondImage)
                <img
                    src="{{ $secondImage }}"
                    alt="{{ $product->name }} alternate angle"
                    loading="lazy"
                    class="absolute inset-0 w-full h-full object-cover object-center opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                >
            @endif
        </a>

        <!-- Editorial Status Badges -->
        <div class="absolute top-3 left-3 flex flex-col gap-1.5 pointer-events-none">
            @if($product->has_discount)
                <span class="bg-[#B85435] text-white text-[10px] font-mono-tech uppercase tracking-[0.18em] px-2.5 py-1 font-semibold">
                    -{{ $product->discount_percentage }}% ARCHIVE
                </span>
            @elseif($product->is_new_arrival)
                <span class="bg-[#0E0E0E] text-[#F7F5F0] text-[10px] font-mono-tech uppercase tracking-[0.18em] px-2.5 py-1">
                    NEW SEASON
                </span>
            @elseif($product->is_best_seller)
                <span class="bg-[#EFECE6]/95 border border-[#0E0E0E] text-[#0E0E0E] text-[10px] font-mono-tech uppercase tracking-[0.18em] px-2.5 py-1 font-semibold">
                    BEST SELLER
                </span>
            @endif

            @if($product->sneaker_style)
                <span class="bg-white/90 border border-[#0E0E0E]/20 text-[#0E0E0E] text-[9px] font-mono-tech uppercase tracking-[0.16em] px-2 py-0.5">
                    {{ $product->sneaker_style }}
                </span>
            @endif
        </div>

        <!-- Gallery Shot Count Pill -->
        @if($galleryCount > 1)
            <span class="absolute bottom-14 right-3 bg-[#0E0E0E]/80 text-white text-[9px] font-mono-tech uppercase tracking-widest px-2 py-0.5 pointer-events-none">
                {{ $galleryCount }} SHOTS
            </span>
        @endif

        <!-- Wishlist Button -->
        <button
            type="button"
            onclick="window.savageToggleWishlist({{ $product->id }}, this)"
            class="absolute top-3 right-3 w-9 h-9 flex items-center justify-center border border-[#0E0E0E]/20 bg-[#F7F5F0]/90 hover:bg-[#0E0E0E] hover:text-white transition {{ $inWishlist ? 'bg-[#0E0E0E] text-[#B85435]' : 'text-[#0E0E0E]' }}"
            title="Save to Wishlist"
        >
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
        </button>

        <!-- Quick Add to Bag Bar -->
        <div class="absolute inset-x-0 bottom-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-gradient-to-t from-black/85 via-black/45 to-transparent">
            @if($product->stock_quantity > 0)
                <button
                    type="button"
                    onclick="window.savageQuickAdd({{ $product->id }}, this, '{{ addslashes($defaultSize) }}', '{{ addslashes($defaultColor) }}', 1)"
                    class="w-full py-2.5 px-4 bg-[#F7F5F0] text-[#0E0E0E] hover:bg-[#B85435] hover:text-white text-[11px] font-bold uppercase tracking-[0.2em] transition"
                >
                    + Quick Add (Size {{ $defaultSize }})
                </button>
            @else
                <span class="block w-full py-2.5 px-4 bg-neutral-900 text-neutral-400 text-center text-[11px] font-mono-tech uppercase tracking-[0.2em]">
                    Sold Out
                </span>
            @endif
        </div>
    </div>

    <!-- Product Metadata -->
    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
        <div>
            <div class="flex items-center justify-between gap-2 text-[10px] font-mono-tech uppercase tracking-[0.18em] text-neutral-500">
                <span class="font-bold text-[#B85435]">{{ $product->display_brand }}</span>
                <span>{{ $product->category?->name ?? 'ARCHIVE' }} • ★ {{ number_format($product->average_rating, 1) }}</span>
            </div>

            <h3 class="mt-1 text-sm font-semibold text-[#0E0E0E] group-hover:text-[#B85435] transition line-clamp-2 leading-snug">
                <a href="{{ route('shop.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>
        </div>

        <div class="pt-2 border-t border-[#E2DDD3] flex items-baseline justify-between gap-2">
            <div class="flex items-baseline gap-2">
                <span class="font-mono-tech text-sm font-bold text-[#0E0E0E]">
                    {{ $product->formatted_effective_price }}
                </span>
                @if($product->has_discount)
                    <span class="font-mono-tech text-xs text-neutral-400 line-through">
                        {{ $product->formatted_price }}
                    </span>
                @endif
            </div>

            <!-- Color Swatch Dots -->
            <div class="flex items-center gap-1">
                @foreach(array_slice($product->available_colors, 0, 3) as $swatch)
                    <span
                        class="w-3 h-3 rounded-full border border-black/30"
                        style="background-color: {{ $swatch['hex'] }}"
                        title="{{ $swatch['name'] }}"
                    ></span>
                @endforeach
            </div>
        </div>
    </div>
</article>
