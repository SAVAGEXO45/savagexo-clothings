<x-layouts.admin title="Orders Management" heading="Orders & Fulfillment Ledger">
    <div class="space-y-6">
        <!-- Filter Bar -->
        <form action="{{ route('admin.orders') }}" method="GET" class="bg-white border border-[#E2DDD3] p-5 flex flex-wrap items-center gap-3 text-xs">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Search order #, tracking code, patron name or email..."
                class="bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-2.5 flex-1 min-w-[240px]"
            >
            <select name="status" class="bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5">
                <option value="">All Order Statuses</option>
                @foreach(['pending', 'confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled'] as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                        {{ strtoupper(str_replace('_', ' ', $st)) }}
                    </option>
                @endforeach
            </select>
            <select name="payment_status" class="bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5">
                <option value="">All Payment Statuses</option>
                @foreach(['pending', 'paid', 'failed', 'refunded'] as $pst)
                    <option value="{{ $pst }}" {{ request('payment_status') === $pst ? 'selected' : '' }}>
                        {{ strtoupper($pst) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn-savage-primary px-6 py-2.5 font-bold uppercase tracking-widest">
                Filter Orders
            </button>
        </form>

        <!-- Orders Table -->
        <div class="bg-white border border-[#E2DDD3] overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#EFECE6] font-mono-tech text-[10px] uppercase tracking-widest text-neutral-600 border-b border-[#E2DDD3]">
                    <tr>
                        <th class="p-4">Order # / Tracking</th>
                        <th class="p-4">Patron</th>
                        <th class="p-4">Destination</th>
                        <th class="p-4">Payment</th>
                        <th class="p-4">Fulfillment Status</th>
                        <th class="p-4">Total (NGN)</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2DDD3]">
                    @foreach($orders as $order)
                        <tr class="hover:bg-[#F7F5F0]">
                            <td class="p-4 font-mono-tech">
                                <p class="font-bold">{{ $order->order_number }}</p>
                                <p class="text-[10px] text-[#B85435]">{{ $order->tracking_number }}</p>
                            </td>
                            <td class="p-4">
                                <p class="font-bold">{{ $order->customer_name }}</p>
                                <p class="text-[11px] text-neutral-500">{{ $order->customer_email }}</p>
                            </td>
                            <td class="p-4">
                                <p>{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                                <p class="font-mono-tech text-[10px] text-neutral-500 uppercase">{{ $order->shipping_method }}</p>
                            </td>
                            <td class="p-4 font-mono-tech">
                                <span class="uppercase text-[11px] font-bold">{{ $order->payment_method }}</span>
                                <span class="block text-[10px] uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-700 font-bold' : 'text-amber-700' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="font-mono-tech text-[10px] uppercase px-2.5 py-1 bg-[#0E0E0E] text-[#C9A96E]">
                                    {{ str_replace('_', ' ', $order->order_status) }}
                                </span>
                            </td>
                            <td class="p-4 font-mono-tech font-bold">{{ $order->formatted_total }}</td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn-savage-outline px-3.5 py-1.5 font-bold uppercase tracking-widest text-[11px]">
                                    Inspect & Update →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $orders->links() }}
        </div>
    </div>
</x-layouts.admin>
