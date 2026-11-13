<x-layouts.admin title="Sneaker & Fashion Brands" heading="Sneaker & Fashion Brand Management">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Add New Brand Form -->
        <div class="lg:col-span-4">
            <form action="{{ route('admin.brands.store') }}" method="POST" class="bg-white border border-[#0E0E0E] p-6 space-y-5 text-xs">
                @csrf
                <div class="border-b border-[#E2DDD3] pb-3">
                    <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">BRAND DIRECTORY</p>
                    <h2 class="font-editorial text-2xl font-bold">Add New Sneaker / Fashion Brand</h2>
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Brand Name *</label>
                    <input
                        type="text"
                        name="name"
                        required
                        placeholder="e.g. Nike, Adidas, Salomon, Balenciaga"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5"
                    >
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Description</label>
                    <textarea
                        name="description"
                        rows="3"
                        placeholder="Brief curation overview for this brand..."
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5"
                    ></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Brand Type *</label>
                        <select name="type" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3 py-2.5">
                            <option value="sneaker">Sneaker Brand</option>
                            <option value="fashion">Fashion Label</option>
                            <option value="luxury">Luxury House</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Sort Order</label>
                        <input
                            type="number"
                            name="sort_order"
                            value="10"
                            class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech"
                        >
                    </div>
                </div>

                <div class="space-y-2 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" checked class="accent-[#0E0E0E]">
                        <span class="font-bold uppercase">Featured on Homepage Brand Bar</span>
                    </label>
                </div>

                <button type="submit" class="w-full btn-savage-primary py-3.5 font-bold uppercase tracking-[0.2em]">
                    Save Brand
                </button>
            </form>
        </div>

        <!-- Right: Existing Brands Table -->
        <div class="lg:col-span-8">
            <div class="bg-white border border-[#E2DDD3] overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#EFECE6] font-mono-tech text-[10px] uppercase tracking-widest text-neutral-600 border-b border-[#E2DDD3]">
                        <tr>
                            <th class="p-4">Brand</th>
                            <th class="p-4">Slug</th>
                            <th class="p-4">Type</th>
                            <th class="p-4">Products</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2DDD3]">
                        @foreach($brands as $brand)
                            <tr class="hover:bg-[#F7F5F0]">
                                <td class="p-4">
                                    <p class="font-bold text-[#0E0E0E]">{{ $brand->name }}</p>
                                    @if($brand->description)
                                        <p class="text-[11px] text-neutral-500 line-clamp-1">{{ $brand->description }}</p>
                                    @endif
                                </td>
                                <td class="p-4 font-mono-tech text-[11px]">{{ $brand->slug }}</td>
                                <td class="p-4 font-mono-tech text-[10px] uppercase">
                                    <span class="px-2 py-0.5 bg-[#0E0E0E] text-[#C9A96E]">{{ $brand->type ?? 'sneaker' }}</span>
                                </td>
                                <td class="p-4 font-mono-tech font-bold">
                                    {{ $brand->products_count }} products
                                </td>
                                <td class="p-4 text-right space-x-3">
                                    <a href="{{ route('shop.sneakers', ['brand' => $brand->slug]) }}" target="_blank" class="font-mono-tech text-[11px] uppercase text-[#B85435] hover:underline">
                                        View ↗
                                    </a>
                                    <form action="{{ route('admin.brands.delete', $brand) }}" method="POST" class="inline" onsubmit="return confirm('Delete this brand?')">
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
    </div>
</x-layouts.admin>
