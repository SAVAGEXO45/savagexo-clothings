<x-layouts.app title="Order Confirmation">
    <section class="max-w-4xl mx-auto px-4 sm:px-8 py-14">
        <div class="bg-[#EFECE6] border border-[#0E0E0E] p-8 sm:p-12 space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#D5CFC2] pb-6">
                <div>
                    <span class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">
                        ATELIER RECEIPT & DISPATCH DOSSIER
                    </span>
                    <h1 class="font-editorial text-3xl sm:text-5xl font-bold mt-1">
                        Thank You, {{ $order->customer_name }}
                    </h1>
                </div>
                <div class="text-left sm:text-right font-mono-tech">
                    <span class="inline-block px-3 py-1 text-xs uppercase font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-900 text-white' : 'bg-[#0E0E0E] text-[#C9A96E]' }}">
                        PAYMENT: {{ strtoupper($order->payment_status) }}
                    </span>
                    <p class="text-xs text-neutral-600 mt-1">ORDER: <strong>{{ $order->order_number }}</strong></p>
                </div>
            </div>

            <!-- Key Identifiers -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-white border border-[#E2DDD3] p-5 text-xs">
                <div>
                    <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-400">ORDER NUMBER</p>
                    <p class="font-mono-tech font-bold text-sm mt-1">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-400">TRACKING CODE</p>
                    <p class="font-mono-tech font-bold text-sm text-[#B85435] mt-1">{{ $order->tracking_number }}</p>
                </div>
                <div>
                    <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-400">PAYMENT METHOD</p>
                    <p class="font-mono-tech font-bold text-sm uppercase mt-1">{{ $order->payment_method }} ({{ $order->payment_status }})</p>
                </div>
            </div>

            @if($order->payment_status !== 'paid' && $order->payment_method !== 'cod' && $order->latestPayment)
                <div class="bg-amber-50 border border-amber-400 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                    <span>Your payment has not yet been settled. You may re-open the secure payment gateway below.</span>
                    <a href="{{ route('payment.gateway', $order->latestPayment->transaction_reference) }}" class="btn-savage-primary px-5 py-2.5 font-bold uppercase tracking-widest text-center">
                        Retry Payment →
                    </a>
                </div>
            @endif

            <!-- Order Tracking Progress -->
            <div class="space-y-4">
                <h2 class="font-editorial text-2xl font-bold">Live Fulfillment Status</h2>
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                    @foreach($order->timeline_steps as $step)
                        <div class="p-3.5 border {{ $step['completed'] ? 'border-[#0E0E0E] bg-white' : 'border-[#D5CFC2] bg-[#F7F5F0] opacity-60' }}">
                            <p class="font-mono-tech text-[10px] uppercase tracking-widest {{ $step['completed'] ? 'text-[#B85435] font-bold' : 'text-neutral-400' }}">
                                {{ $step['completed'] ? '● COMPLETED' : '○ UPCOMING' }}
                            </p>
                            <p class="text-xs font-bold mt-1">{{ $step['label'] }}</p>
                            <p class="text-[11px] text-neutral-500 mt-0.5">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Items Ordered -->
            <div class="space-y-3">
                <h2 class="font-editorial text-2xl font-bold">Reserved Garments</h2>
                <div class="bg-white border border-[#E2DDD3] divide-y divide-[#E2DDD3]">
                    @foreach($order->items as $item)
                        <div class="p-4 flex items-center justify-between gap-4 text-xs">
                            <div class="flex items-center gap-4">
                                <img src="{{ $item->product_image ?: '/images/products/agbada-noir.jpg' }}" alt="{{ $item->product_name }}" class="w-12 h-16 object-cover bg-[#EFECE6]">
                                <div>
                                    <p class="font-bold text-[#0E0E0E]">{{ $item->product_name }}</p>
                                    <p class="font-mono-tech text-[11px] text-neutral-500">
                                        Size: {{ $item->size }} • Color: {{ $item->color }} • Qty: {{ $item->quantity }}
                                    </p>
                                </div>
                            </div>
                            <span class="font-mono-tech font-bold">₦{{ number_format((float) $item->line_total, 0) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Totals & Destination -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                <div class="bg-white border border-[#E2DDD3] p-5 text-xs space-y-1.5">
                    <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">DELIVERY DESTINATION</p>
                    <p class="font-bold">{{ $order->customer_name }}</p>
                    <p class="text-neutral-600">{{ $order->shipping_address }}</p>
                    <p class="text-neutral-600">{{ $order->shipping_city }}, {{ $order->shipping_state }}, {{ $order->shipping_country }}</p>
                    <p class="font-mono-tech text-neutral-500 pt-1">{{ $order->customer_phone }}</p>
                </div>

                <div class="bg-white border border-[#E2DDD3] p-5 text-xs space-y-2">
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Subtotal</span>
                        <span class="font-mono-tech">₦{{ number_format((float) $order->subtotal, 0) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-[#B85435]">
                            <span>Discount ({{ $order->coupon_code }})</span>
                            <span class="font-mono-tech">-₦{{ number_format((float) $order->discount_amount, 0) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Shipping ({{ strtoupper($order->shipping_method) }})</span>
                        <span class="font-mono-tech">₦{{ number_format((float) $order->shipping_fee, 0) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-[#E2DDD3] text-sm font-bold">
                        <span>Total Amount</span>
                        <span class="font-mono-tech">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 pt-4">
                <a href="{{ route('orders.track', ['code' => $order->tracking_number]) }}" class="btn-savage-outline px-6 py-3.5 text-xs font-bold uppercase tracking-[0.2em]">
                    Live Order Tracker →
                </a>
                <a href="{{ route('shop.index') }}" class="btn-savage-primary px-8 py-3.5 text-xs font-bold uppercase tracking-[0.2em]">
                    Continue Shopping
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
