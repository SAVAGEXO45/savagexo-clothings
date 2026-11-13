<x-layouts.admin title="Executive Analytics" heading="Atelier Executive Command Center">
    <div class="space-y-10">
        <!-- 1. KPI Summary Strip -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="bg-[#0E0E0E] text-white p-6 border border-[#0E0E0E]">
                <p class="font-mono-tech text-[10px] uppercase tracking-[0.22em] text-[#C9A96E]">TOTAL VERIFIED REVENUE</p>
                <p class="font-mono-tech text-3xl font-bold mt-2">₦{{ number_format($totalRevenue, 0) }}</p>
                <p class="text-xs text-neutral-400 mt-1">{{ $paidOrdersCount }} settled orders via Paystack/Flutterwave</p>
            </div>

            <div class="bg-white p-6 border border-[#0E0E0E]">
                <p class="font-mono-tech text-[10px] uppercase tracking-[0.22em] text-neutral-500">TOTAL ORDERS</p>
                <p class="font-mono-tech text-3xl font-bold text-[#0E0E0E] mt-2">{{ $totalOrders }}</p>
                <p class="text-xs text-[#B85435] font-semibold mt-1">{{ $pendingOrdersCount }} active in fulfillment queue</p>
            </div>

            <div class="bg-white p-6 border border-[#E2DDD3]">
                <p class="font-mono-tech text-[10px] uppercase tracking-[0.22em] text-neutral-500">REGISTERED PATRONS</p>
                <p class="font-mono-tech text-3xl font-bold text-[#0E0E0E] mt-2">{{ $totalCustomers }}</p>
                <p class="text-xs text-neutral-500 mt-1">Active private client accounts</p>
            </div>

            <div class="bg-white p-6 border border-[#E2DDD3]">
                <p class="font-mono-tech text-[10px] uppercase tracking-[0.22em] text-neutral-500">CATALOG & SNEAKER VAULT</p>
                <p class="font-mono-tech text-3xl font-bold text-[#0E0E0E] mt-2">{{ $totalProducts }}</p>
                <p class="text-xs text-neutral-500 mt-1">{{ $totalSneakers ?? 0 }} sneakers across {{ $totalBrands ?? 0 }} global brands</p>
            </div>
        </div>

        <!-- 2. Recent Orders & Top Selling Pieces -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            <!-- Recent Orders Table -->
            <div class="xl:col-span-8 bg-white border border-[#E2DDD3]">
                <div class="p-6 border-b border-[#E2DDD3] flex items-center justify-between">
                    <div>
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">LIVE DISPATCH LEDGER</p>
                        <h2 class="font-editorial text-2xl font-bold">Recent Client Orders</h2>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="text-xs font-bold uppercase tracking-widest hover:text-[#B85435]">Manage All Orders →</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#EFECE6] font-mono-tech text-[10px] uppercase tracking-wider text-neutral-600 border-b border-[#E2DDD3]">
                            <tr>
                                <th class="p-4">Order #</th>
                                <th class="p-4">Patron</th>
                                <th class="p-4">Gateway</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Amount</th>
                                <th class="p-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2DDD3]">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-[#F7F5F0]">
                                    <td class="p-4 font-mono-tech font-bold">{{ $order->order_number }}</td>
                                    <td class="p-4">
                                        <p class="font-semibold">{{ $order->customer_name }}</p>
                                        <p class="text-[11px] text-neutral-500">{{ $order->shipping_city }}</p>
                                    </td>
                                    <td class="p-4 font-mono-tech uppercase text-[11px]">
                                        {{ $order->payment_method }}
                                        <span class="block text-[10px] {{ $order->payment_status === 'paid' ? 'text-emerald-700 font-bold' : 'text-amber-700' }}">
                                            ({{ $order->payment_status }})
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span class="font-mono-tech text-[10px] uppercase px-2.5 py-1 bg-[#0E0E0E] text-[#C9A96E]">
                                            {{ str_replace('_', ' ', $order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-mono-tech font-bold">{{ $order->formatted_total }}</td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="font-bold uppercase tracking-wider text-[#B85435] hover:underline">
                                            Manage →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column: Top Selling Pieces & Low Stock Alerts -->
            <div class="xl:col-span-4 space-y-8">
                <div class="bg-white border border-[#E2DDD3] p-6 space-y-4">
                    <div>
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">VELOCITY LEADERBOARD</p>
                        <h3 class="font-editorial text-2xl font-bold">Top-Selling Pieces</h3>
                    </div>
                    <div class="divide-y divide-[#E2DDD3]">
                        @foreach($topSellingProducts as $prod)
                            <div class="py-3 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="{{ $prod->primary_image_url }}" alt="{{ $prod->name }}" class="w-10 h-12 object-cover flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="font-bold truncate">{{ $prod->name }}</p>
                                        <p class="font-mono-tech text-[10px] text-neutral-500">{{ $prod->formatted_effective_price }}</p>
                                    </div>
                                </div>
                                <span class="font-mono-tech text-xs font-bold text-[#B85435]">{{ $prod->sales_count }} sold</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white border border-[#E2DDD3] p-6 space-y-4">
                    <div>
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-amber-700">INVENTORY WATCH</p>
                        <h3 class="font-editorial text-2xl font-bold">Low Stock Alerts (≤ 5 Units)</h3>
                    </div>
                    @if($lowStockProducts->isEmpty())
                        <p class="text-xs text-emerald-700 font-mono-tech">✓ All catalog pieces have healthy stock reserves.</p>
                    @else
                        <div class="divide-y divide-[#E2DDD3]">
                            @foreach($lowStockProducts as $low)
                                <div class="py-2.5 flex items-center justify-between text-xs">
                                    <span class="font-semibold truncate pr-2">{{ $low->name }}</span>
                                    <a href="{{ route('admin.products.edit', $low) }}" class="font-mono-tech text-red-700 font-bold hover:underline">
                                        {{ $low->stock_quantity }} left →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
