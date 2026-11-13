<x-layouts.admin title="Products & Inventory" heading="Products & Inventory Management">
    <div class="space-y-6">
        <!-- Search & Filter Bar -->
        <div class="bg-white border border-[#E2DDD3] p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <form action="{{ route('admin.products') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search by garment/sneaker name, brand, or SKU..."
                    class="bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-2.5 text-xs w-full sm:w-64"
                >
                <select name="product_type" class="bg-[#F7F5F0] border border-[#0E0E0E] px-3 py-2.5 text-xs">
                    <option value="">All Product Types</option>
                    <option value="clothing" {{ request('product_type') === 'clothing' ? 'selected' : '' }}>Clothing</option>
                    <option value="sneaker" {{ request('product_type') === 'sneaker' ? 'selected' : '' }}>Sneakers</option>
                    <option value="accessory" {{ request('product_type') === 'accessory' ? 'selected' : '' }}>Accessories</option>
                </select>
                <select name="brand_id" class="bg-[#F7F5F0] border border-[#0E0E0E] px-3 py-2.5 text-xs">
                    <option value="">All Brands</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" {{ (string) request('brand_id') === (string) $b->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
                <select name="category_id" class="bg-[#F7F5F0] border border-[#0E0E0E] px-3 py-2.5 text-xs">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string) request('category_id') === (string) $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-savage-primary px-5 py-2.5 text-xs font-bold uppercase tracking-widest">
                    Filter
                </button>
            </form>

            <a href="{{ route('admin.products.create') }}" class="btn-savage-accent px-6 py-2.5 text-xs font-bold uppercase tracking-[0.18em] text-center">
                + Add Clothing / Sneaker
            </a>
        </div>

        <!-- Products Table -->
        <div class="bg-white border border-[#E2DDD3] overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#EFECE6] font-mono-tech text-[10px] uppercase tracking-widest text-neutral-600 border-b border-[#E2DDD3]">
                    <tr>
                        <th class="p-4">Piece</th>
                        <th class="p-4">Brand / Style</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Price (NGN)</th>
                        <th class="p-4">Stock</th>
                        <th class="p-4">Gallery & Variants</th>
                        <th class="p-4">Flags</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2DDD3]">
                    @foreach($products as $product)
                        <tr class="hover:bg-[#F7F5F0]">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-11 h-14 object-cover border border-[#E2DDD3]">
                                    <div>
                                        <p class="font-bold text-[#0E0E0E]">{{ $product->name }}</p>
                                        <p class="font-mono-tech text-[10px] text-neutral-500 uppercase">{{ $product->sku }} • {{ $product->gender }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 bg-[#0E0E0E] text-[#C9A96E] font-mono-tech text-[10px] uppercase">
                                    {{ $product->display_brand }}
                                </span>
                                @if($product->sneaker_style)
                                    <span class="block font-mono-tech text-[10px] text-neutral-500 mt-1">{{ $product->sneaker_style }}</span>
                                @endif
                            </td>
                            <td class="p-4">{{ $product->category?->name }}</td>
                            <td class="p-4 font-mono-tech">
                                <span class="font-bold">{{ $product->formatted_effective_price }}</span>
                                @if($product->has_discount)
                                    <span class="block text-[10px] text-neutral-400 line-through">{{ $product->formatted_price }}</span>
                                @endif
                            </td>
                            <td class="p-4 font-mono-tech">
                                <span class="px-2 py-0.5 {{ $product->stock_quantity > 5 ? 'bg-emerald-50 text-emerald-800' : 'bg-red-50 text-red-800 font-bold' }}">
                                    {{ $product->stock_quantity }} units
                                </span>
                            </td>
                            <td class="p-4 font-mono-tech text-[11px]">
                                <span class="font-bold text-[#B85435]">{{ $product->images->count() }} shots</span>
                                <span class="block text-[10px] text-neutral-500">{{ $product->variants->count() }} size/colour variants</span>
                            </td>
                            <td class="p-4 space-x-1 font-mono-tech text-[10px]">
                                @if($product->is_featured)
                                    <span class="px-1.5 py-0.5 bg-[#C9A96E] text-[#0E0E0E] font-bold">FEAT</span>
                                @endif
                                @if($product->is_new_arrival)
                                    <span class="px-1.5 py-0.5 bg-[#0E0E0E] text-white">NEW</span>
                                @endif
                                @if($product->is_best_seller)
                                    <span class="px-1.5 py-0.5 bg-[#B85435] text-white">BEST</span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-3">
                                <a href="{{ route('admin.products.edit', $product) }}" class="font-bold uppercase tracking-wider text-[#0E0E0E] hover:text-[#B85435]">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.delete', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-bold uppercase tracking-wider text-red-700 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $products->links() }}
        </div>
    </div>
</x-layouts.admin>
