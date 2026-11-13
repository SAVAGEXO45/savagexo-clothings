<x-layouts.admin title="Customers" heading="Registered Client Accounts">
    <div class="bg-white border border-[#E2DDD3] overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#EFECE6] font-mono-tech text-[10px] uppercase tracking-widest text-neutral-600 border-b border-[#E2DDD3]">
                <tr>
                    <th class="p-4">Name</th>
                    <th class="p-4">Email & Phone</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Orders</th>
                    <th class="p-4">Total Verified Spend</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E2DDD3]">
                @foreach($customers as $cust)
                    <tr class="hover:bg-[#F7F5F0]">
                        <td class="p-4 font-bold">{{ $cust->name }}</td>
                        <td class="p-4">
                            <p>{{ $cust->email }}</p>
                            <p class="font-mono-tech text-[11px] text-neutral-500">{{ $cust->phone ?? '—' }}</p>
                        </td>
                        <td class="p-4 font-mono-tech uppercase text-[11px]">
                            <span class="px-2 py-0.5 {{ $cust->role === 'admin' ? 'bg-[#0E0E0E] text-[#C9A96E]' : 'bg-[#EFECE6]' }}">
                                {{ $cust->role }}
                            </span>
                        </td>
                        <td class="p-4 font-mono-tech font-bold">{{ $cust->orders_count }}</td>
                        <td class="p-4 font-mono-tech font-bold">₦{{ number_format((float) ($cust->paid_spend ?? 0), 0) }}</td>
                        <td class="p-4">
                            <span class="font-mono-tech text-[10px] uppercase px-2 py-0.5 {{ $cust->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ $cust->is_active ? 'Active' : 'Suspended' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            @if($cust->id !== auth()->id())
                                <form action="{{ route('admin.customers.toggle', $cust) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="font-mono-tech text-[11px] uppercase tracking-wider {{ $cust->is_active ? 'text-red-700' : 'text-emerald-700' }} hover:underline">
                                        {{ $cust->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $customers->links() }}
    </div>
</x-layouts.admin>
