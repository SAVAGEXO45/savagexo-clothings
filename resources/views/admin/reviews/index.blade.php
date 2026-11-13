<x-layouts.admin title="Customer Reviews" heading="Client Reviews & Moderation">
    <div class="bg-white border border-[#E2DDD3] overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#EFECE6] font-mono-tech text-[10px] uppercase tracking-widest text-neutral-600 border-b border-[#E2DDD3]">
                <tr>
                    <th class="p-4">Piece</th>
                    <th class="p-4">Patron</th>
                    <th class="p-4">Rating & Review</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E2DDD3]">
                @foreach($reviews as $rev)
                    <tr>
                        <td class="p-4 font-bold">{{ $rev->product?->name }}</td>
                        <td class="p-4">
                            <p class="font-semibold">{{ $rev->user?->name }}</p>
                            @if($rev->is_verified_purchase)
                                <span class="font-mono-tech text-[10px] text-emerald-700">✓ Verified Buyer</span>
                            @endif
                        </td>
                        <td class="p-4 max-w-md">
                            <p class="text-[#B85435] font-bold">{{ str_repeat('★', $rev->rating) }} — {{ $rev->title }}</p>
                            <p class="text-neutral-600 mt-0.5">{{ $rev->comment }}</p>
                        </td>
                        <td class="p-4">
                            <span class="font-mono-tech text-[10px] uppercase px-2 py-0.5 {{ $rev->is_approved ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $rev->is_approved ? 'Approved' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-3">
                            <form action="{{ route('admin.reviews.toggle', $rev) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="font-mono-tech text-[11px] uppercase hover:underline">
                                    {{ $rev->is_approved ? 'Hide' : 'Approve' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.delete', $rev) }}" method="POST" class="inline">
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
</x-layouts.admin>
