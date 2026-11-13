<x-layouts.app title="Order History">
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            @include('account.partials.sidebar')

            <div class="lg:col-span-9 space-y-6">
                <div class="border-b border-[#E2DDD3] pb-4 flex items-center justify-between">
                    <div>
                        <p class="font-mono-tech text-xs uppercase tracking-[0.22em] text-[#B85435]">ARCHIVE PURCHASES</p>
                        <h1 class="font-editorial text-3xl sm:text-4xl font-bold mt-1">Order History & Tracking</h1>
                    </div>
                </div>

                @if($orders->isEmpty())
                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-12 text-center space-y-4">
                        <p class="text-xs text-neutral-600">You have not placed any orders yet.</p>
                        <a href="{{ route('shop.index') }}" class="inline-block btn-savage-primary px-6 py-3 text-xs font-bold uppercase tracking-widest">Explore Shop</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <div class="bg-white border border-[#E2DDD3] p-6 space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#E2DDD3] pb-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-3">
                                            <span class="font-mono-tech text-sm font-bold">{{ $order->order_number }}</span>
                                            <span class="font-mono-tech text-[10px] uppercase px-2.5 py-0.5 bg-[#0E0E0E] text-[#C9A96E]">
                                                {{ str_replace('_', ' ', $order->order_status) }}
                                            </span>
                                            <span class="font-mono-tech text-[10px] uppercase px-2.5 py-0.5 {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $order->payment_status }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-neutral-500">
                                            Placed {{ $order->created_at->format('d M Y, H:i') }} • Tracking: <strong class="font-mono-tech text-[#0E0E0E]">{{ $order->tracking_number }}</strong>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="font-mono-tech text-lg font-bold">{{ $order->formatted_total }}</span>
                                        <a href="{{ route('account.orders.show', $order->order_number) }}" class="btn-savage-outline px-4 py-2 text-xs font-bold uppercase tracking-widest">
                                            Details & Track →
                                        </a>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-4">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center gap-3 text-xs bg-[#F7F5F0] p-2.5 border border-[#E2DDD3]">
                                            <img src="{{ $item->product_image ?: '/images/products/agbada-noir.jpg' }}" alt="{{ $item->product_name }}" class="w-10 h-12 object-cover">
                                            <div>
                                                <p class="font-semibold">{{ $item->product_name }}</p>
                                                <p class="font-mono-tech text-[10px] text-neutral-500">{{ $item->size }} / {{ $item->color }} × {{ $item->quantity }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>
