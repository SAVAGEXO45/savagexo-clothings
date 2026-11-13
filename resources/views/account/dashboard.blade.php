<x-layouts.app title="Client Portal">
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            @include('account.partials.sidebar')

            <div class="lg:col-span-9 space-y-10">
                <!-- Stat Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-[#EFECE6] border border-[#0E0E0E] p-6">
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-500">TOTAL ARCHIVE SPEND</p>
                        <p class="font-mono-tech text-2xl font-bold text-[#0E0E0E] mt-2">₦{{ number_format($totalSpent, 0) }}</p>
                    </div>
                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-6">
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-500">TOTAL ORDERS PLACED</p>
                        <p class="font-mono-tech text-2xl font-bold text-[#0E0E0E] mt-2">{{ $user->orders()->count() }}</p>
                    </div>
                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-6">
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-neutral-500">WISHLISTED PIECES</p>
                        <p class="font-mono-tech text-2xl font-bold text-[#B85435] mt-2">{{ $user->wishlists()->count() }}</p>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-[#E2DDD3] pb-3">
                        <h2 class="font-editorial text-2xl font-bold">Recent Orders</h2>
                        <a href="{{ route('account.orders') }}" class="text-xs font-bold uppercase tracking-widest hover:text-[#B85435]">View All →</a>
                    </div>

                    @if($recentOrders->isEmpty())
                        <div class="bg-[#EFECE6] border border-[#E2DDD3] p-8 text-center text-xs text-neutral-600">
                            You have not placed any orders yet. <a href="{{ route('shop.index') }}" class="font-bold underline text-[#B85435]">Explore our latest collection.</a>
                        </div>
                    @else
                        <div class="bg-white border border-[#E2DDD3] overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-[#EFECE6] font-mono-tech text-[10px] uppercase tracking-widest text-neutral-600 border-b border-[#E2DDD3]">
                                    <tr>
                                        <th class="p-4">Order #</th>
                                        <th class="p-4">Date</th>
                                        <th class="p-4">Payment</th>
                                        <th class="p-4">Fulfillment</th>
                                        <th class="p-4">Total</th>
                                        <th class="p-4 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E2DDD3]">
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td class="p-4 font-mono-tech font-bold">{{ $order->order_number }}</td>
                                            <td class="p-4 text-neutral-500">{{ $order->created_at->format('d M Y') }}</td>
                                            <td class="p-4">
                                                <span class="font-mono-tech text-[10px] uppercase px-2 py-0.5 {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-900' : 'bg-amber-100 text-amber-900' }}">
                                                    {{ $order->payment_status }}
                                                </span>
                                            </td>
                                            <td class="p-4 font-mono-tech uppercase text-[11px]">{{ str_replace('_', ' ', $order->order_status) }}</td>
                                            <td class="p-4 font-mono-tech font-bold">{{ $order->formatted_total }}</td>
                                            <td class="p-4 text-right">
                                                <a href="{{ route('account.orders.show', $order->order_number) }}" class="font-bold uppercase tracking-wider text-[#B85435] hover:underline">
                                                    Inspect →
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Saved Addresses Preview -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-[#E2DDD3] pb-3">
                        <h2 class="font-editorial text-2xl font-bold">Saved Delivery Addresses</h2>
                        <a href="{{ route('account.addresses') }}" class="text-xs font-bold uppercase tracking-widest hover:text-[#B85435]">Manage Addresses →</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($addresses as $addr)
                            <div class="bg-white border border-[#E2DDD3] p-5 text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold uppercase tracking-wider">{{ $addr->label }}</span>
                                    @if($addr->is_default)
                                        <span class="font-mono-tech text-[10px] bg-[#0E0E0E] text-[#C9A96E] px-2 py-0.5">DEFAULT</span>
                                    @endif
                                </div>
                                <p class="font-semibold pt-1">{{ $addr->recipient_name }}</p>
                                <p class="text-neutral-600">{{ $addr->full_address }}</p>
                                <p class="font-mono-tech text-neutral-500">{{ $addr->phone }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-neutral-500">No addresses saved yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
