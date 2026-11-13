<x-layouts.app title="Atelier Checkout">
    <section
        x-data="{
            shippingMethod: 'standard',
            paymentMethod: 'paystack',
            subtotal: {{ (float) $summary['subtotal'] }},
            discount: {{ (float) $summary['discount'] }},
            rates: {
                standard: {{ (float) $summary['shipping_rates']['standard'] }},
                express: {{ (float) $summary['shipping_rates']['express'] }},
                lagos_same_day: {{ (float) $summary['shipping_rates']['lagos_same_day'] }}
            },
            get currentShippingFee() {
                return this.rates[this.shippingMethod] || 0;
            },
            get currentTotal() {
                return Math.max(0, (this.subtotal - this.discount) + this.currentShippingFee);
            },
            formatNaira(val) {
                return '₦' + Math.round(val).toLocaleString('en-NG');
            },
            fillAddress(addr) {
                document.getElementById('customer_name').value = addr.recipient_name;
                document.getElementById('customer_phone').value = addr.phone;
                document.getElementById('shipping_address').value = addr.street_address + (addr.apartment ? ', ' + addr.apartment : '');
                document.getElementById('shipping_city').value = addr.city;
                document.getElementById('shipping_state').value = addr.state;
                document.getElementById('shipping_postal_code').value = addr.postal_code || '';
            }
        }"
        class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12"
    >
        <div class="border-b border-[#E2DDD3] pb-6 flex items-end justify-between">
            <div>
                <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">ATELIER DISPATCH & SETTLEMENT</p>
                <h1 class="font-editorial text-4xl sm:text-5xl font-bold mt-1">Secure Checkout</h1>
            </div>
            <a href="{{ route('cart.index') }}" class="text-xs font-bold uppercase tracking-[0.18em] hover:text-[#B85435]">
                ← Return to Bag
            </a>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-12 mt-10">
            @csrf

            <!-- Left Column: Customer Info, Address, Shipping & Payment Method -->
            <div class="lg:col-span-7 space-y-10">
                <!-- Saved Addresses Quick Selector -->
                @if($addresses->isNotEmpty())
                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-6 space-y-3">
                        <p class="font-mono-tech text-xs uppercase tracking-[0.2em] text-[#B85435]">SAVED CLIENT ADDRESSES</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($addresses as $addr)
                                <button
                                    type="button"
                                    @click="fillAddress({{ Js::from($addr) }})"
                                    class="text-left p-3.5 bg-white border border-[#0E0E0E] hover:border-[#B85435] transition text-xs space-y-1"
                                >
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold uppercase tracking-wider">{{ $addr->label }}</span>
                                        @if($addr->is_default)
                                            <span class="font-mono-tech text-[10px] text-[#B85435]">DEFAULT</span>
                                        @endif
                                    </div>
                                    <p class="text-neutral-600 truncate">{{ $addr->street_address }}, {{ $addr->city }}</p>
                                    <p class="font-mono-tech text-[11px] text-neutral-500">{{ $addr->phone }}</p>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 1. Customer & Delivery Information -->
                <div class="space-y-5">
                    <h2 class="font-editorial text-2xl font-bold border-b border-[#E2DDD3] pb-2">1. Patron & Delivery Address</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">Full Recipient Name *</label>
                            <input
                                id="customer_name"
                                type="text"
                                name="customer_name"
                                required
                                value="{{ old('customer_name', $defaultAddress?->recipient_name ?? $user?->name) }}"
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">Email Address (For Receipt & Tracking) *</label>
                            <input
                                type="email"
                                name="customer_email"
                                required
                                value="{{ old('customer_email', $user?->email) }}"
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">Phone Number (WhatsApp / Courier) *</label>
                            <input
                                id="customer_phone"
                                type="text"
                                name="customer_phone"
                                required
                                placeholder="+234 803 000 0000"
                                value="{{ old('customer_phone', $defaultAddress?->phone ?? $user?->phone) }}"
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">Country</label>
                            <input
                                type="text"
                                name="shipping_country"
                                value="{{ old('shipping_country', $defaultAddress?->country ?? 'Nigeria') }}"
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">Street Address & Suite / Apartment *</label>
                        <input
                            id="shipping_address"
                            type="text"
                            name="shipping_address"
                            required
                            placeholder="e.g. 14B Adeola Odeku Street, Victoria Island"
                            value="{{ old('shipping_address', $defaultAddress ? ($defaultAddress->street_address . ($defaultAddress->apartment ? ', ' . $defaultAddress->apartment : '')) : '') }}"
                            class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">City *</label>
                            <input
                                id="shipping_city"
                                type="text"
                                name="shipping_city"
                                required
                                placeholder="Lagos"
                                value="{{ old('shipping_city', $defaultAddress?->city ?? 'Lagos') }}"
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">State *</label>
                            <input
                                id="shipping_state"
                                type="text"
                                name="shipping_state"
                                required
                                placeholder="Lagos State"
                                value="{{ old('shipping_state', $defaultAddress?->state ?? 'Lagos State') }}"
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">Postal Code</label>
                            <input
                                id="shipping_postal_code"
                                type="text"
                                name="shipping_postal_code"
                                placeholder="106104"
                                value="{{ old('shipping_postal_code', $defaultAddress?->postal_code) }}"
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                    </div>

                    @auth
                        <label class="inline-flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" name="save_address" value="1" class="accent-[#0E0E0E]">
                            <span>Save this delivery address to my private client profile</span>
                        </label>
                    @endauth
                </div>

                <!-- 2. Shipping Method -->
                <div class="space-y-4">
                    <h2 class="font-editorial text-2xl font-bold border-b border-[#E2DDD3] pb-2">2. Courier & Delivery Tier</h2>

                    <div class="grid grid-cols-1 gap-3">
                        <label class="flex items-center justify-between p-4 border cursor-pointer transition" :class="shippingMethod === 'standard' ? 'border-[#0E0E0E] bg-[#EFECE6]' : 'border-[#E2DDD3] bg-white'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="standard" x-model="shippingMethod" class="accent-[#0E0E0E]">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider">Standard Nationwide Courier (3–5 Business Days)</p>
                                    <p class="text-[11px] text-neutral-500">Complimentary on orders over ₦{{ number_format($summary['free_shipping_threshold'], 0) }}</p>
                                </div>
                            </div>
                            <span class="font-mono-tech text-xs font-bold">
                                {{ $summary['shipping_rates']['standard'] > 0 ? '₦' . number_format($summary['shipping_rates']['standard'], 0) : 'FREE' }}
                            </span>
                        </label>

                        <label class="flex items-center justify-between p-4 border cursor-pointer transition" :class="shippingMethod === 'express' ? 'border-[#0E0E0E] bg-[#EFECE6]' : 'border-[#E2DDD3] bg-white'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="express" x-model="shippingMethod" class="accent-[#0E0E0E]">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider">DHL Priority Express Air (1–2 Business Days)</p>
                                    <p class="text-[11px] text-neutral-500">Priority dispatch to Lagos, Abuja, Port Harcourt & Global</p>
                                </div>
                            </div>
                            <span class="font-mono-tech text-xs font-bold">₦{{ number_format($summary['shipping_rates']['express'], 0) }}</span>
                        </label>

                        <label class="flex items-center justify-between p-4 border cursor-pointer transition" :class="shippingMethod === 'lagos_same_day' ? 'border-[#0E0E0E] bg-[#EFECE6]' : 'border-[#E2DDD3] bg-white'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="lagos_same_day" x-model="shippingMethod" class="accent-[#0E0E0E]">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider">SAVAGEXO White-Glove Lagos Same-Day Concierge</p>
                                    <p class="text-[11px] text-neutral-500">Direct chauffeur delivery in Victoria Island, Ikoyi & Lekki</p>
                                </div>
                            </div>
                            <span class="font-mono-tech text-xs font-bold">₦{{ number_format($summary['shipping_rates']['lagos_same_day'], 0) }}</span>
                        </label>
                    </div>
                </div>

                <!-- 3. Payment Method -->
                <div class="space-y-4">
                    <h2 class="font-editorial text-2xl font-bold border-b border-[#E2DDD3] pb-2">3. Payment Gateway</h2>
                    <p class="text-xs text-neutral-600">
                        We never store card credentials on SAVAGEXO servers. All transactions are verified cryptographically on our backend.
                    </p>

                    <div class="grid grid-cols-1 gap-3">
                        <label class="flex items-center justify-between p-4 border cursor-pointer transition" :class="paymentMethod === 'paystack' ? 'border-[#0E0E0E] bg-[#EFECE6]' : 'border-[#E2DDD3] bg-white'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="paystack" x-model="paymentMethod" class="accent-[#0E0E0E]">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider">Paystack Checkout (Nigerian Cards, Bank Transfer, USSD)</p>
                                    <p class="text-[11px] text-neutral-500">Instant NGN verification via Paystack</p>
                                </div>
                            </div>
                            <span class="font-mono-tech text-[10px] uppercase tracking-widest px-2.5 py-1 bg-[#0E0E0E] text-[#C9A96E]">RECOMMENDED</span>
                        </label>

                        <label class="flex items-center justify-between p-4 border cursor-pointer transition" :class="paymentMethod === 'flutterwave' ? 'border-[#0E0E0E] bg-[#EFECE6]' : 'border-[#E2DDD3] bg-white'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="flutterwave" x-model="paymentMethod" class="accent-[#0E0E0E]">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider">Flutterwave (Multi-Currency Cards, Barter & Bank Transfer)</p>
                                    <p class="text-[11px] text-neutral-500">Seamless Pan-African & international card settlement</p>
                                </div>
                            </div>
                            <span class="font-mono-tech text-[10px] uppercase tracking-widest px-2.5 py-1 border border-[#0E0E0E]">SECURE</span>
                        </label>

                        @if($codEnabled)
                            <label class="flex items-center justify-between p-4 border cursor-pointer transition" :class="paymentMethod === 'cod' ? 'border-[#0E0E0E] bg-[#EFECE6]' : 'border-[#E2DDD3] bg-white'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="accent-[#0E0E0E]">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider">Pay on Delivery (POS / Bank Transfer upon Arrival)</p>
                                        <p class="text-[11px] text-neutral-500">Available for verified Lagos & Abuja metro deliveries</p>
                                    </div>
                                </div>
                                <span class="font-mono-tech text-[10px] uppercase tracking-widest px-2.5 py-1 border border-[#0E0E0E]">COD</span>
                            </label>
                        @endif
                    </div>
                </div>

                <!-- Special Notes -->
                <div>
                    <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">Atelier / Delivery Notes (Optional)</label>
                    <textarea
                        name="notes"
                        rows="2"
                        placeholder="Gate code, preferred delivery window, or gift message..."
                        class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                    >{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Right Column: Order Summary & Final CTA -->
            <div class="lg:col-span-5">
                <div class="bg-[#EFECE6] border border-[#0E0E0E] p-7 space-y-6 sticky top-28">
                    <h2 class="font-editorial text-2xl font-bold border-b border-[#D5CFC2] pb-3">Bag Summary ({{ $summary['item_count'] }} Items)</h2>

                    <div class="divide-y divide-[#D5CFC2] max-h-80 overflow-y-auto pr-1">
                        @foreach($summary['items'] as $item)
                            <div class="py-3.5 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <img src="{{ $item->product?->primary_image_url }}" alt="{{ $item->product?->name }}" class="w-14 h-18 object-cover border border-[#E2DDD3]">
                                    <div>
                                        <p class="text-xs font-bold text-[#0E0E0E] line-clamp-1">{{ $item->product?->name }}</p>
                                        <p class="font-mono-tech text-[10px] text-neutral-500">
                                            Size: {{ $item->size }} • {{ $item->color }} • Qty: {{ $item->quantity }}
                                        </p>
                                    </div>
                                </div>
                                <span class="font-mono-tech text-xs font-bold">₦{{ number_format($item->line_total, 0) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 text-xs border-y border-[#D5CFC2] py-5">
                        <div class="flex justify-between">
                            <span class="text-neutral-600 uppercase tracking-wider">Subtotal</span>
                            <span class="font-mono-tech font-bold">₦{{ number_format($summary['subtotal'], 0) }}</span>
                        </div>

                        @if($summary['discount'] > 0)
                            <div class="flex justify-between text-[#B85435]">
                                <span class="uppercase tracking-wider">Privilege Code ({{ $summary['coupon']?->code }})</span>
                                <span class="font-mono-tech font-bold">-₦{{ number_format($summary['discount'], 0) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="text-neutral-600 uppercase tracking-wider">Courier Fee</span>
                            <span class="font-mono-tech font-bold" x-text="currentShippingFee > 0 ? formatNaira(currentShippingFee) : 'COMPLIMENTARY'"></span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between">
                        <span class="text-xs font-bold uppercase tracking-[0.2em]">Total Payable</span>
                        <span class="font-mono-tech text-2xl font-bold text-[#0E0E0E]" x-text="formatNaira(currentTotal)">
                            ₦{{ number_format($summary['total'], 0) }}
                        </span>
                    </div>

                    <button type="submit" class="w-full btn-savage-primary py-4 text-xs font-bold uppercase tracking-[0.22em]">
                        Complete Order & Proceed →
                    </button>
                </div>
            </div>
        </form>
    </section>
</x-layouts.app>
