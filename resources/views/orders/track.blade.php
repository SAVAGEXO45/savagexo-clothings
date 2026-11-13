<x-layouts.app title="Track Your Order">
    <section class="max-w-4xl mx-auto px-4 sm:px-8 py-14 space-y-10">
        <div class="text-center space-y-3">
            <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#B85435]">SAVAGEXO LOGISTICS CONCIERGE</p>
            <h1 class="font-editorial text-4xl sm:text-5xl font-bold">Track Your Order</h1>
            <p class="text-xs text-neutral-600 max-w-lg mx-auto">
                Enter your Order Number (e.g. <code class="font-mono-tech font-bold">SXO-261112-B74M9</code>) or Tracking Number (e.g. <code class="font-mono-tech font-bold">TRK-SXO-7731904B</code>).
            </p>
        </div>

        <form action="{{ route('orders.track') }}" method="GET" class="flex flex-col sm:flex-row gap-3 max-w-2xl mx-auto">
            <input
                type="text"
                name="code"
                value="{{ $query }}"
                required
                placeholder="Enter Order Number or Tracking Code..."
                class="flex-1 bg-white border border-[#0E0E0E] px-4 py-4 text-xs font-mono-tech uppercase focus:outline-none"
            >
            <button type="submit" class="btn-savage-primary px-8 py-4 text-xs font-bold uppercase tracking-[0.2em]">
                Locate Shipment
            </button>
        </form>

        @if($query !== '' && !$order)
            <div class="bg-[#EFECE6] border border-[#0E0E0E] p-10 text-center space-y-2">
                <p class="font-mono-tech text-xs uppercase tracking-widest text-red-700">NO SHIPMENT FOUND</p>
                <p class="text-xs text-neutral-600">We could not find an order matching <strong>{{ $query }}</strong>. Please verify your code and try again.</p>
            </div>
        @endif

        @if($order)
            <div class="bg-[#EFECE6] border border-[#0E0E0E] p-8 space-y-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#D5CFC2] pb-5">
                    <div>
                        <p class="font-mono-tech text-xs text-[#B85435]">TRACKING NUMBER: {{ $order->tracking_number }}</p>
                        <h2 class="font-editorial text-3xl font-bold mt-1">Order {{ $order->order_number }}</h2>
                    </div>
                    <div class="font-mono-tech text-xs">
                        <span class="px-3 py-1.5 bg-[#0E0E0E] text-[#C9A96E] uppercase font-bold">
                            STATUS: {{ strtoupper(str_replace('_', ' ', $order->order_status)) }}
                        </span>
                    </div>
                </div>

                <!-- 5-Step Progress Timeline -->
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                    @foreach($order->timeline_steps as $step)
                        <div class="p-4 border {{ $step['completed'] ? 'border-[#0E0E0E] bg-white' : 'border-[#D5CFC2] bg-[#F7F5F0] opacity-60' }}">
                            <p class="font-mono-tech text-[10px] uppercase tracking-widest {{ $step['completed'] ? 'text-[#B85435] font-bold' : 'text-neutral-400' }}">
                                {{ $step['completed'] ? '✓ COMPLETE' : 'PENDING' }}
                            </p>
                            <p class="text-xs font-bold mt-1.5">{{ $step['label'] }}</p>
                            <p class="text-[11px] text-neutral-500 mt-1">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div class="bg-white border border-[#E2DDD3] p-5 space-y-1">
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-400">DESTINATION</p>
                        <p class="font-bold">{{ $order->customer_name }}</p>
                        <p class="text-neutral-600">{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                    </div>
                    <div class="bg-white border border-[#E2DDD3] p-5 space-y-1">
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-400">DISPATCH SUMMARY</p>
                        <p class="font-bold">{{ $order->items->sum('quantity') }} Pieces — {{ $order->formatted_total }}</p>
                        <p class="text-neutral-600">Payment Status: <strong class="uppercase">{{ $order->payment_status }}</strong></p>
                    </div>
                </div>
            </div>
        @endif
    </section>
</x-layouts.app>
