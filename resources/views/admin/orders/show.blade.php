<x-layouts.admin :title="'Order ' . $order->order_number" :heading="'Order Dossier: ' . $order->order_number">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Order Items & Customer Details -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white border border-[#E2DDD3] p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-[#E2DDD3] pb-3">
                    <h2 class="font-editorial text-2xl font-bold">Ordered Garments</h2>
                    <span class="font-mono-tech text-xs text-[#B85435]">TRACKING: {{ $order->tracking_number }}</span>
                </div>

                <div class="divide-y divide-[#E2DDD3]">
                    @foreach($order->items as $item)
                        <div class="py-4 flex items-center justify-between gap-4 text-xs">
                            <div class="flex items-center gap-4">
                                <img src="{{ $item->product_image ?: '/images/products/agbada-noir.jpg' }}" alt="{{ $item->product_name }}" class="w-14 h-18 object-cover border border-[#E2DDD3]">
                                <div>
                                    <p class="font-bold text-sm">{{ $item->product_name }}</p>
                                    <p class="font-mono-tech text-neutral-500">SKU: {{ $item->product_sku }} • Size: {{ $item->size }} • Color: {{ $item->color }}</p>
                                    <p class="font-mono-tech text-neutral-500">Unit: ₦{{ number_format((float) $item->unit_price, 0) }} × {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <span class="font-mono-tech font-bold text-sm">₦{{ number_format((float) $item->line_total, 0) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-[#E2DDD3] pt-4 space-y-1.5 text-xs text-right">
                    <p>Subtotal: <strong class="font-mono-tech">₦{{ number_format((float) $order->subtotal, 0) }}</strong></p>
                    @if($order->discount_amount > 0)
                        <p class="text-[#B85435]">Discount ({{ $order->coupon_code }}): <strong class="font-mono-tech">-₦{{ number_format((float) $order->discount_amount, 0) }}</strong></p>
                    @endif
                    <p>Shipping ({{ strtoupper($order->shipping_method) }}): <strong class="font-mono-tech">₦{{ number_format((float) $order->shipping_fee, 0) }}</strong></p>
                    <p class="text-base font-bold pt-1">Total: <strong class="font-mono-tech">{{ $order->formatted_total }}</strong></p>
                </div>
            </div>

            <!-- Payment Verification Logs -->
            <div class="bg-white border border-[#E2DDD3] p-6 space-y-4">
                <h3 class="font-editorial text-2xl font-bold">Payment Verification Ledger</h3>
                @forelse($order->payments as $pay)
                    <div class="p-4 bg-[#F7F5F0] border border-[#E2DDD3] text-xs font-mono-tech space-y-1">
                        <p>GATEWAY: <strong>{{ strtoupper($pay->gateway) }}</strong> | STATUS: <strong class="uppercase">{{ $pay->status }}</strong></p>
                        <p>REFERENCE: {{ $pay->transaction_reference }} | CHANNEL: {{ $pay->channel }}</p>
                        <p>AMOUNT: ₦{{ number_format((float) $pay->amount, 0) }} ({{ $pay->currency }})</p>
                    </div>
                @empty
                    <p class="text-xs text-neutral-500">No payment transaction records logged.</p>
                @endforelse
            </div>
        </div>

        <!-- Right: Status Update Controls & Customer Info -->
        <div class="lg:col-span-4 space-y-6">
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="bg-white border border-[#0E0E0E] p-6 space-y-5 text-xs">
                @csrf
                @method('PATCH')
                <h2 class="font-editorial text-2xl font-bold border-b border-[#E2DDD3] pb-2">Update Order & Dispatch Status</h2>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Fulfillment Status</label>
                    <select name="order_status" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-3 font-mono-tech uppercase">
                        @foreach(['pending', 'confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled'] as $st)
                            <option value="{{ $st }}" {{ $order->order_status === $st ? 'selected' : '' }}>
                                {{ strtoupper(str_replace('_', ' ', $st)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Payment Status</label>
                    <select name="payment_status" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-3 font-mono-tech uppercase">
                        @foreach(['pending', 'paid', 'failed', 'refunded'] as $pst)
                            <option value="{{ $pst }}" {{ $order->payment_status === $pst ? 'selected' : '' }}>
                                {{ strtoupper($pst) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full btn-savage-primary py-3.5 font-bold uppercase tracking-[0.2em]">
                    Save Status Update
                </button>
            </form>

            <div class="bg-white border border-[#E2DDD3] p-6 space-y-2 text-xs">
                <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">PATRON & DELIVERY ADDRESS</p>
                <p class="font-bold text-sm">{{ $order->customer_name }}</p>
                <p class="text-neutral-600">{{ $order->customer_email }}</p>
                <p class="font-mono-tech text-neutral-600">{{ $order->customer_phone }}</p>
                <hr class="border-[#E2DDD3] my-2">
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
                <p>{{ $order->shipping_country }}</p>
                @if($order->notes)
                    <p class="pt-2 text-[#B85435]"><strong>Notes:</strong> {{ $order->notes }}</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
