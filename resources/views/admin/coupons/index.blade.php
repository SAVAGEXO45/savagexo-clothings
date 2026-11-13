<x-layouts.admin title="Discount Coupons" heading="Privilege & Discount Coupons">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Create Coupon -->
        <div class="lg:col-span-4">
            <form action="{{ route('admin.coupons.store') }}" method="POST" class="bg-white border border-[#0E0E0E] p-6 space-y-4 text-xs">
                @csrf
                <h2 class="font-editorial text-2xl font-bold border-b border-[#E2DDD3] pb-2">Create Privilege Code</h2>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Coupon Code *</label>
                    <input type="text" name="code" required placeholder="e.g. SAVAGE20" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech uppercase">
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Description</label>
                    <input type="text" name="description" placeholder="20% VIP Seasonal Discount" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1">Discount Type *</label>
                        <select name="type" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3 py-2.5">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (₦)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1">Value *</label>
                        <input type="number" step="0.01" name="value" required placeholder="15" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1">Min Order (₦)</label>
                        <input type="number" name="min_order_amount" value="50000" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech">
                    </div>
                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1">Max Discount (₦)</label>
                        <input type="number" name="max_discount_amount" placeholder="50000" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech">
                    </div>
                </div>

                <button type="submit" class="w-full btn-savage-primary py-3.5 font-bold uppercase tracking-[0.2em]">
                    Create Coupon
                </button>
            </form>
        </div>

        <!-- Right: Active Coupons -->
        <div class="lg:col-span-8 bg-white border border-[#E2DDD3] overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#EFECE6] font-mono-tech text-[10px] uppercase tracking-widest text-neutral-600 border-b border-[#E2DDD3]">
                    <tr>
                        <th class="p-4">Code</th>
                        <th class="p-4">Discount</th>
                        <th class="p-4">Min Spend</th>
                        <th class="p-4">Used</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2DDD3]">
                    @foreach($coupons as $coupon)
                        <tr>
                            <td class="p-4">
                                <p class="font-mono-tech font-bold text-[#B85435]">{{ $coupon->code }}</p>
                                <p class="text-[11px] text-neutral-500">{{ $coupon->description }}</p>
                            </td>
                            <td class="p-4 font-mono-tech font-bold">
                                {{ $coupon->type === 'percentage' ? (int) $coupon->value . '%' : '₦' . number_format((float) $coupon->value, 0) }}
                            </td>
                            <td class="p-4 font-mono-tech">₦{{ number_format((float) $coupon->min_order_amount, 0) }}</td>
                            <td class="p-4 font-mono-tech">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                            <td class="p-4">
                                <span class="font-mono-tech text-[10px] uppercase px-2 py-0.5 {{ $coupon->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-neutral-200 text-neutral-600' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-3">
                                <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="font-mono-tech text-[11px] uppercase hover:underline">
                                        {{ $coupon->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.coupons.delete', $coupon) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-mono-tech text-[11px] uppercase text-red-700 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
