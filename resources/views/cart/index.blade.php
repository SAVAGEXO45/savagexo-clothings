<x-layouts.app title="Shopping Bag">
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12">
        <div class="border-b border-[#E2DDD3] pb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">ATELIER SELECTION</p>
                <h1 class="font-editorial text-4xl sm:text-5xl font-bold mt-1">Your Shopping Bag ({{ $summary['item_count'] }})</h1>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-bold uppercase tracking-[0.2em] hover:text-[#B85435]">
                ← Continue Shopping
            </a>
        </div>

        @if($summary['items']->isEmpty())
            <!-- Empty Bag State Illustration -->
            <div class="my-16 bg-[#EFECE6] border border-[#E2DDD3] p-16 text-center max-w-2xl mx-auto space-y-6">
                <div class="w-16 h-16 mx-auto border border-[#0E0E0E] flex items-center justify-center bg-[#F7F5F0]">
                    <svg class="w-7 h-7 text-[#0E0E0E]" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
                <div class="space-y-2">
                    <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">YOUR BAG IS CURRENTLY EMPTY</p>
                    <h2 class="font-editorial text-3xl font-bold">Curate Your Wardrobe</h2>
                    <p class="text-xs text-neutral-600 max-w-md mx-auto leading-relaxed">
                        Explore our latest Lagos-to-Milan ready-to-wear archive, bespoke Agbada couture, and 600GSM luxury streetwear.
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('shop.index') }}" class="inline-block btn-savage-primary px-8 py-4 text-xs font-bold uppercase tracking-[0.22em]">
                        Explore All Collections →
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mt-10">
                <!-- Left: Bag Items -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Free Shipping Progress Indicator -->
                    @php
                        $threshold = $summary['free_shipping_threshold'];
                        $remaining = max(0, $threshold - $summary['subtotal']);
                        $pct = $threshold > 0 ? min(100, round(($summary['subtotal'] / $threshold) * 100)) : 100;
                    @endphp
                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-5 space-y-2.5">
                        @if($remaining <= 0)
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-800">
                                ✓ Your order qualifies for <strong class="font-bold">COMPLIMENTARY STANDARD DELIVERY</strong> across Nigeria.
                            </p>
                        @else
                            <p class="text-xs uppercase tracking-wider">
                                Add <strong class="font-mono-tech text-[#B85435]">₦{{ number_format($remaining, 0) }}</strong> more to unlock <strong class="font-bold">COMPLIMENTARY DELIVERY</strong>.
                            </p>
                        @endif
                        <div class="w-full h-1.5 bg-[#D5CFC2] overflow-hidden">
                            <div class="h-full bg-[#B85435] transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="divide-y divide-[#E2DDD3] border-y border-[#E2DDD3]">
                        @foreach($summary['items'] as $item)
                            <div class="py-6 flex flex-col sm:flex-row gap-6 items-start sm:items-center justify-between">
                                <div class="flex gap-5 items-center">
                                    <a href="{{ $item->product ? route('shop.show', $item->product->slug) : '#' }}" class="w-24 h-32 bg-[#EFECE6] border border-[#E2DDD3] flex-shrink-0 overflow-hidden">
                                        <img src="{{ $item->product?->primary_image_url ?? '/images/products/agbada-noir.jpg' }}" alt="{{ $item->product?->name }}" class="w-full h-full object-cover">
                                    </a>
                                    <div class="space-y-1.5">
                                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-500">
                                            {{ $item->product?->category?->name ?? 'SAVAGEXO' }} // {{ $item->product?->sku }}
                                        </p>
                                        <h3 class="font-editorial text-xl font-bold text-[#0E0E0E]">
                                            @if($item->product)
                                                <a href="{{ route('shop.show', $item->product->slug) }}" class="hover:text-[#B85435]">{{ $item->product->name }}</a>
                                            @else
                                                SAVAGEXO Archive Piece
                                            @endif
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-3 text-xs text-neutral-600 font-mono-tech">
                                            <span>SIZE: <strong class="text-[#0E0E0E]">{{ $item->size }}</strong></span>
                                            <span>•</span>
                                            <span class="inline-flex items-center gap-1.5">
                                                COLOR:
                                                @if($item->color_hex)
                                                    <span class="w-3 h-3 rounded-full border border-black/30 inline-block" style="background-color: {{ $item->color_hex }}"></span>
                                                @endif
                                                <strong class="text-[#0E0E0E]">{{ $item->color }}</strong>
                                            </span>
                                        </div>
                                        <p class="font-mono-tech text-xs text-neutral-500 pt-1">
                                            Unit Price: ₦{{ number_format((float) $item->unit_price, 0) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Quantity Update & Remove Controls -->
                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center border border-[#0E0E0E] bg-white">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            name="quantity"
                                            value="{{ max(1, $item->quantity - 1) }}"
                                            class="w-9 h-9 flex items-center justify-center hover:bg-[#EFECE6]"
                                        >−</button>
                                        <span class="w-10 text-center font-mono-tech text-xs font-bold">{{ $item->quantity }}</span>
                                        <button
                                            type="submit"
                                            name="quantity"
                                            value="{{ $item->quantity + 1 }}"
                                            class="w-9 h-9 flex items-center justify-center hover:bg-[#EFECE6]"
                                        >+</button>
                                    </form>

                                    <div class="text-right min-w-[110px]">
                                        <p class="font-mono-tech text-sm font-bold text-[#0E0E0E]">
                                            ₦{{ number_format($item->line_total, 0) }}
                                        </p>
                                        <form action="{{ route('cart.remove', $item) }}" method="POST" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[11px] font-mono-tech uppercase tracking-wider text-neutral-400 hover:text-red-700">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right: Order Summary & Privilege Code -->
                <div class="lg:col-span-4">
                    <div class="bg-[#EFECE6] border border-[#0E0E0E] p-7 space-y-6 sticky top-28">
                        <h2 class="font-editorial text-2xl font-bold border-b border-[#D5CFC2] pb-3">Order Summary</h2>

                        <!-- Privilege Coupon Form -->
                        <div>
                            @if($summary['coupon'])
                                <div class="bg-white border border-[#0E0E0E] p-3.5 flex items-center justify-between text-xs">
                                    <div>
                                        <p class="font-mono-tech font-bold text-[#B85435]">CODE: {{ $summary['coupon']->code }}</p>
                                        <p class="text-[11px] text-neutral-500">{{ $summary['coupon']->description }}</p>
                                    </div>
                                    <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[11px] font-mono-tech uppercase text-red-700 hover:underline">Remove</button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('cart.coupon.apply') }}" method="POST" class="space-y-2">
                                    @csrf
                                    <label class="block text-[10px] font-mono-tech uppercase tracking-widest text-neutral-600">
                                        Privilege / Discount Code (Try: SAVAGE15 or LAGOSVIP)
                                    </label>
                                    <div class="flex gap-2">
                                        <input
                                            type="text"
                                            name="coupon_code"
                                            placeholder="Enter code..."
                                            class="flex-1 bg-white border border-[#0E0E0E] px-3 py-2.5 text-xs uppercase font-mono-tech focus:outline-none"
                                        >
                                        <button type="submit" class="px-4 py-2.5 bg-[#0E0E0E] text-white text-xs font-bold uppercase tracking-widest hover:bg-[#B85435] transition">
                                            Apply
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>

                        <!-- Totals Breakdown -->
                        <div class="space-y-3 text-xs border-y border-[#D5CFC2] py-5">
                            <div class="flex justify-between">
                                <span class="text-neutral-600 uppercase tracking-wider">Subtotal</span>
                                <span class="font-mono-tech font-bold">₦{{ number_format($summary['subtotal'], 0) }}</span>
                            </div>

                            @if($summary['discount'] > 0)
                                <div class="flex justify-between text-[#B85435]">
                                    <span class="uppercase tracking-wider">Privilege Rebate ({{ $summary['coupon']?->code }})</span>
                                    <span class="font-mono-tech font-bold">-₦{{ number_format($summary['discount'], 0) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between">
                                <span class="text-neutral-600 uppercase tracking-wider">Standard Delivery</span>
                                <span class="font-mono-tech font-bold">
                                    {{ $summary['shipping_fee'] > 0 ? '₦' . number_format($summary['shipping_fee'], 0) : 'COMPLIMENTARY' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-baseline justify-between">
                            <span class="text-xs font-bold uppercase tracking-[0.2em]">Estimated Total</span>
                            <span class="font-mono-tech text-2xl font-bold text-[#0E0E0E]">
                                ₦{{ number_format($summary['total'], 0) }}
                            </span>
                        </div>

                        <a
                            href="{{ route('checkout.index') }}"
                            class="block w-full text-center btn-savage-primary py-4 text-xs font-bold uppercase tracking-[0.22em]"
                        >
                            Proceed to Secure Checkout →
                        </a>

                        <div class="pt-2 text-center space-y-1 text-[11px] font-mono-tech text-neutral-500">
                            <p>🔒 256-BIT ENCRYPTED CHECKOUT</p>
                            <p>PAYSTACK • FLUTTERWAVE • CASH ON DELIVERY</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
</x-layouts.app>
