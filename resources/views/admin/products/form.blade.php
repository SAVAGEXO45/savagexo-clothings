<x-layouts.admin
    :title="$product ? 'Edit ' . $product->name : 'Create New Product'"
    :heading="$product ? 'Edit Catalog Piece: ' . $product->name : 'Add New Clothing or Sneaker Product'"
>
    @php
        $existingSizes = $product ? implode(', ', $product->allVariants->pluck('size')->unique()->toArray()) : 'S, M, L, XL';
        $existingColors = $product
            ? implode(', ', $product->allVariants->unique('color')->map(fn($v) => $v->color . ':' . $v->color_hex)->toArray())
            : 'Obsidian Black:#111111, Ivory Cream:#F5F2EB, Burnt Terracotta:#B85435';
    @endphp

    <div class="space-y-10 max-w-5xl">
        <form
            action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="bg-white border border-[#0E0E0E] p-8 space-y-8"
        >
            @csrf
            @if($product)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">
                <div class="sm:col-span-2">
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Product Title *</label>
                    <input
                        type="text"
                        name="name"
                        required
                        value="{{ old('name', $product?->name) }}"
                        placeholder="e.g. Nike Air Force 1 '07 Triple White or Obsidian Virgin Wool Overcoat"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3"
                    >
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">SKU Code</label>
                    <input
                        type="text"
                        name="sku"
                        value="{{ old('sku', $product?->sku) }}"
                        placeholder="SXO-SNK-025"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3 font-mono-tech uppercase"
                    >
                </div>
            </div>

            <!-- Brand, Product Type & Sneaker Style Row -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 text-xs bg-[#EFECE6] p-5 border border-[#D5CFC2]">
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Product Type *</label>
                    <select name="product_type" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                        <option value="clothing" {{ old('product_type', $product?->product_type ?? 'clothing') === 'clothing' ? 'selected' : '' }}>Clothing Garment</option>
                        <option value="sneaker" {{ old('product_type', $product?->product_type) === 'sneaker' ? 'selected' : '' }}>Sneaker / Footwear</option>
                        <option value="accessory" {{ old('product_type', $product?->product_type) === 'accessory' ? 'selected' : '' }}>Luxury Accessory</option>
                    </select>
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Select Brand</label>
                    <select name="brand_id" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                        <option value="">SAVAGEXO / Custom Brand</option>
                        @foreach($brands ?? [] as $b)
                            <option value="{{ $b->id }}" {{ (int) old('brand_id', $product?->brand_id) === $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Or Custom Brand Name</label>
                    <input
                        type="text"
                        name="brand_name"
                        value="{{ old('brand_name', $product?->brand_name ?? 'SAVAGEXO') }}"
                        placeholder="Nike, Adidas, SAVAGEXO..."
                        class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5"
                    >
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Sneaker Style (If Sneaker)</label>
                    <select name="sneaker_style" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                        <option value="">None / Clothing</option>
                        @foreach(['Running', 'Basketball', 'Lifestyle', 'Casual', 'Retro', 'High-top', 'Low-top', 'Training', 'Luxury', 'Streetwear'] as $st)
                            <option value="{{ $st }}" {{ old('sneaker_style', $product?->sneaker_style) === $st ? 'selected' : '' }}>
                                {{ $st }} Sneakers
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 text-xs">
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Department / Category *</label>
                    <select name="category_id" required class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-3">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (int) old('category_id', $product?->category_id) === $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Gender Edit *</label>
                    <select name="gender" required class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-3">
                        <option value="men" {{ old('gender', $product?->gender) === 'men' ? 'selected' : '' }}>Men</option>
                        <option value="women" {{ old('gender', $product?->gender) === 'women' ? 'selected' : '' }}>Women</option>
                        <option value="unisex" {{ old('gender', $product?->gender ?? 'unisex') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                    </select>
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Regular Price (₦ NGN) *</label>
                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        required
                        value="{{ old('price', $product?->price) }}"
                        placeholder="185000"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3 font-mono-tech"
                    >
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Discount Price (₦ Optional)</label>
                    <input
                        type="number"
                        step="0.01"
                        name="discount_price"
                        value="{{ old('discount_price', $product?->discount_price) }}"
                        placeholder="155000"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3 font-mono-tech"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Total Stock Quantity *</label>
                    <input
                        type="number"
                        name="stock_quantity"
                        required
                        value="{{ old('stock_quantity', $product?->stock_quantity ?? 20) }}"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3 font-mono-tech"
                    >
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Sizes (Clothing S–XXL or Shoe 38–46)</label>
                    <input
                        type="text"
                        name="sizes_csv"
                        value="{{ old('sizes_csv', $existingSizes) }}"
                        placeholder="S, M, L, XL or 40, 41, 42, 43, 44, 45"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3 font-mono-tech"
                    >
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Colours (Name:#HEX, Comma Separated)</label>
                    <input
                        type="text"
                        name="colors_csv"
                        value="{{ old('colors_csv', $existingColors) }}"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3 font-mono-tech"
                    >
                </div>
            </div>

            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Short Editorial Summary</label>
                    <input
                        type="text"
                        name="short_description"
                        value="{{ old('short_description', $product?->short_description) }}"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3"
                    >
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Full Product Description *</label>
                    <textarea
                        name="description"
                        rows="4"
                        required
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3"
                    >{{ old('description', $product?->description) }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Material & Upper / Fabric Composition</label>
                    <input
                        type="text"
                        name="material"
                        value="{{ old('material', $product?->material) }}"
                        placeholder="Full-Grain Leather & Air Cushioning / 100% Virgin Wool"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3"
                    >
                </div>
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Care Instructions</label>
                    <input
                        type="text"
                        name="care_instructions"
                        value="{{ old('care_instructions', $product?->care_instructions) }}"
                        placeholder="Specialist care recommended."
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3"
                    >
                </div>
            </div>

            <!-- Image Upload & Multi-Angle Gallery Addition Section -->
            <div class="space-y-5 text-xs border-t border-[#E2DDD3] pt-6">
                <h3 class="font-editorial text-2xl font-bold">Product Photography & Multi-Angle Gallery</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Primary Image Path / URL</label>
                        <input
                            type="text"
                            name="featured_image_url"
                            value="{{ old('featured_image_url', $product?->featured_image ?? '/images/products/agbada-noir.jpg') }}"
                            class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-2.5 font-mono-tech"
                        >
                    </div>

                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Or Upload Primary Image</label>
                        <input type="file" name="featured_image_file" accept="image/*" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3 py-2">
                    </div>

                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Upload Multiple Gallery Images</label>
                        <input type="file" name="gallery_files[]" multiple accept="image/*" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3 py-2">
                    </div>
                </div>

                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1.5">
                        Add Gallery Image URLs by Line (Format: <code class="text-[#B85435]">/path/or/url.jpg|View Angle|Colour Name</code>)
                    </label>
                    <textarea
                        name="gallery_urls_text"
                        rows="3"
                        placeholder="/images/sneakers/nike-air-force-1-07-triple-white-lateral.jpg|Lateral Side Profile|Triple White&#10;/images/sneakers/nike-air-force-1-07-triple-white-heel.jpg|Heel & Outsole View|Triple White"
                        class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-2.5 font-mono-tech text-[11px]"
                    ></textarea>
                </div>
            </div>

            <!-- Merchandising Flags -->
            <div class="flex flex-wrap items-center gap-6 text-xs border-t border-[#E2DDD3] pt-6">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }} class="accent-[#0E0E0E]">
                    <span class="font-bold uppercase">Active in Store</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product?->is_featured ?? true) ? 'checked' : '' }} class="accent-[#0E0E0E]">
                    <span class="font-bold uppercase">Feature on Homepage</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival', $product?->is_new_arrival ?? true) ? 'checked' : '' }} class="accent-[#0E0E0E]">
                    <span class="font-bold uppercase">New Arrival</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $product?->is_best_seller ?? false) ? 'checked' : '' }} class="accent-[#0E0E0E]">
                    <span class="font-bold uppercase">Best Seller</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_trending" value="1" {{ old('is_trending', $product?->is_trending ?? false) ? 'checked' : '' }} class="accent-[#0E0E0E]">
                    <span class="font-bold uppercase">Trending Streetwear</span>
                </label>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="btn-savage-primary px-8 py-4 text-xs font-bold uppercase tracking-[0.2em]">
                    {{ $product ? 'Save Product Changes' : 'Publish Product' }}
                </button>
                <a href="{{ route('admin.products') }}" class="text-xs font-bold uppercase tracking-widest text-neutral-500 hover:text-[#0E0E0E]">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Existing Gallery Images Management (Edit / Delete Individual Product Images) -->
        @if($product && $product->images->isNotEmpty())
            <div class="bg-white border border-[#0E0E0E] p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-[#E2DDD3] pb-3">
                    <div>
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">GALLERY ASSET MANAGER</p>
                        <h3 class="font-editorial text-2xl font-bold">Manage Product Gallery Images ({{ $product->images->count() }} Shots)</h3>
                    </div>
                    <a href="{{ route('shop.show', $product->slug) }}" target="_blank" class="font-mono-tech text-xs uppercase text-[#B85435] hover:underline">
                        Preview Live Gallery ↗
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($product->images as $img)
                        <div class="border border-[#E2DDD3] bg-[#F7F5F0] p-4 space-y-3 text-xs">
                            <div class="relative aspect-[4/5] bg-white border border-[#E2DDD3] overflow-hidden">
                                <img src="{{ $img->url }}" alt="{{ $img->alt_text }}" class="w-full h-full object-cover">
                                @if($img->is_primary)
                                    <span class="absolute top-2 left-2 bg-[#0E0E0E] text-[#C9A96E] font-mono-tech text-[9px] uppercase px-2 py-0.5 font-bold">
                                        PRIMARY COVER
                                    </span>
                                @endif
                            </div>

                            <!-- Edit Image Metadata Form -->
                            <form action="{{ route('admin.products.images.update', $img) }}" method="POST" class="space-y-2">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="block font-mono-tech text-[10px] uppercase text-neutral-500">Image Path / URL</label>
                                    <input type="text" name="image_path" value="{{ $img->image_path }}" class="w-full bg-white border border-[#0E0E0E] px-2.5 py-1.5 font-mono-tech text-[10px]">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block font-mono-tech text-[10px] uppercase text-neutral-500">View Angle</label>
                                        <input type="text" name="view_angle" value="{{ $img->view_angle }}" placeholder="Front View" class="w-full bg-white border border-[#0E0E0E] px-2.5 py-1.5 text-[11px]">
                                    </div>
                                    <div>
                                        <label class="block font-mono-tech text-[10px] uppercase text-neutral-500">Colour Match</label>
                                        <input type="text" name="color_name" value="{{ $img->color_name }}" placeholder="Obsidian Black" class="w-full bg-white border border-[#0E0E0E] px-2.5 py-1.5 text-[11px]">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-1">
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                        <input type="checkbox" name="is_primary" value="1" {{ $img->is_primary ? 'checked' : '' }} class="accent-[#0E0E0E]">
                                        <span class="font-mono-tech text-[10px] uppercase">Set Primary</span>
                                    </label>
                                    <button type="submit" class="px-3 py-1 bg-[#0E0E0E] text-white font-mono-tech text-[10px] uppercase tracking-wider hover:bg-[#B85435]">
                                        Update Shot
                                    </button>
                                </div>
                            </form>

                            <!-- Delete Image Form -->
                            <form action="{{ route('admin.products.images.delete', $img) }}" method="POST" onsubmit="return confirm('Delete this gallery image?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-1.5 border border-red-300 text-red-700 font-mono-tech text-[10px] uppercase hover:bg-red-50">
                                    Delete Image
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.admin>
