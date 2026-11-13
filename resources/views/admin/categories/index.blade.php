<x-layouts.admin title="Categories" heading="Manage Product Categories">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Create Category Form -->
        <div class="lg:col-span-4">
            <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white border border-[#0E0E0E] p-6 space-y-4 text-xs">
                @csrf
                <h2 class="font-editorial text-2xl font-bold border-b border-[#E2DDD3] pb-2">Add Department Category</h2>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Category Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Resort Couture" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5">
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Gender Group *</label>
                    <select name="gender_group" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5">
                        <option value="unisex">Unisex</option>
                        <option value="men">Men</option>
                        <option value="women">Women</option>
                        <option value="accessories">Accessories</option>
                    </select>
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Cover Image Path</label>
                    <input type="text" name="image" value="/images/products/agbada-noir.jpg" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech">
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5"></textarea>
                </div>

                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="accent-[#0E0E0E]">
                        <span>Featured on Homepage</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="accent-[#0E0E0E]">
                        <span>Active</span>
                    </label>
                </div>

                <button type="submit" class="w-full btn-savage-primary py-3.5 font-bold uppercase tracking-[0.2em]">
                    Create Category
                </button>
            </form>
        </div>

        <!-- Right: Categories List -->
        <div class="lg:col-span-8 bg-white border border-[#E2DDD3] overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#EFECE6] font-mono-tech text-[10px] uppercase tracking-widest text-neutral-600 border-b border-[#E2DDD3]">
                    <tr>
                        <th class="p-4">Category</th>
                        <th class="p-4">Slug</th>
                        <th class="p-4">Group</th>
                        <th class="p-4">Pieces</th>
                        <th class="p-4">Featured</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2DDD3]">
                    @foreach($categories as $cat)
                        <tr>
                            <td class="p-4 font-bold">{{ $cat->name }}</td>
                            <td class="p-4 font-mono-tech text-neutral-500">{{ $cat->slug }}</td>
                            <td class="p-4 uppercase font-mono-tech text-[11px]">{{ $cat->gender_group }}</td>
                            <td class="p-4 font-mono-tech font-bold">{{ $cat->products_count }}</td>
                            <td class="p-4">
                                @if($cat->is_featured)
                                    <span class="font-mono-tech text-[10px] bg-[#0E0E0E] text-[#C9A96E] px-2 py-0.5">FEATURED</span>
                                @else
                                    <span class="text-neutral-400">—</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.categories.delete', $cat) }}" method="POST" class="inline">
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
