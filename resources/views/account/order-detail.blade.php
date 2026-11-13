<x-layouts.app :title="'Order ' . $order->order_number">
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            @include('account.partials.sidebar')

            <div class="lg:col-span-9 space-y-8">
                <div class="border-b border-[#E2DDD3] pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <p class="font-mono-tech text-xs uppercase tracking-[0.22em] text-[#B85435]">TRACKING CODE: {{ $order->tracking_number }}</p>
                        <h1 class="font-editorial text-3xl sm:text-4xl font-bold mt-1">Order {{ $order->order_number }}</h1>
                    </div>
                    <a href="{{ route('account.orders') }}" class="text-xs font-bold uppercase tracking-widest hover:text-[#B85435]">← Back to Orders</a>
                </div>

                <!-- Timeline -->
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                    @foreach($order->timeline_steps as $step)
                        <div class="p-4 border {{ $step['completed'] ? 'border-[#0E0E0E] bg-white' : 'border-[#D5CFC2] bg-[#EFECE6] opacity-60' }}">
                            <p class="font-mono-tech text-[10px] uppercase tracking-widest {{ $step['completed'] ? 'text-[#B85435] font-bold' : 'text-neutral-400' }}">
                                {{ $step['completed'] ? '● COMPLETED' : '○ UPCOMING' }}
                            </p>
                            <p class="text-xs font-bold mt-1">{{ $step['label'] }}</p>
                            <p class="text-[11px] text-neutral-500 mt-0.5">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Order Items -->
                <div class="bg-white border border-[#E2DDD3] divide-y divide-[#E2DDD3]">
                    @foreach($order->items as $item)
                        <div class="p-5 flex items-center justify-between gap-4 text-xs">
                            <div class="flex items-center gap-4">
                                <img src="{{ $item->product_image ?: '/images/products/agbada-noir.jpg' }}" alt="{{ $item->product_name }}" class="w-14 h-18 object-cover">
                                <div>
                                    <p class="font-bold text-sm">{{ $item->product_name }}</p>
                                    <p class="font-mono-tech text-neutral-500 mt-0.5">Size: {{ $item->size }} • Color: {{ $item->color }} • Qty: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <span class="font-mono-tech font-bold text-sm">₦{{ number_format((float) $item->line_total, 0) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-6 space-y-1.5">
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">SHIPPING ADDRESS</p>
                        <p class="font-bold">{{ $order->customer_name }}</p>
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }}, {{ $order->shipping_country }}</p>
                        <p class="font-mono-tech text-neutral-500">{{ $order->customer_phone }}</p>
                    </div>

                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-6 space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-mono-tech">₦{{ number_format((float) $order->subtotal, 0) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-[#B85435]">
                                <span>Discount ({{ $order->coupon_code }})</span>
                                <span class="font-mono-tech">-₦{{ number_format((float) $order->discount_amount, 0) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span>Delivery ({{ strtoupper($order->shipping_method) }})</span>
                            <span class="font-mono-tech">₦{{ number_format((float) $order->shipping_fee, 0) }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-[#D5CFC2] text-sm font-bold">
                            <span>Total Paid</span>
                            <span class="font-mono-tech">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
