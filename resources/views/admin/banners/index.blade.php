<x-layouts.admin title="Banners & Shipping" heading="Homepage Banners & Delivery Configuration">
    <div class="space-y-12">
        <!-- 1. Configurable Shipping Fees & Store Settings -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white border border-[#0E0E0E] p-8 space-y-6 text-xs">
            @csrf
            @method('PUT')
            <div class="border-b border-[#E2DDD3] pb-3">
                <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">LOGISTICS & CHECKOUT CONFIGURATION</p>
                <h2 class="font-editorial text-2xl font-bold">Configurable Delivery Fees & Store Announcement</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Standard Courier Fee (₦)</label>
                    <input type="number" name="shipping_fee_standard" value="{{ $shippingStandard }}" required class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech">
                </div>
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">DHL Express Air Fee (₦)</label>
                    <input type="number" name="shipping_fee_express" value="{{ $shippingExpress }}" required class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech">
                </div>
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Lagos Same-Day Concierge (₦)</label>
                    <input type="number" name="shipping_fee_lagos_same_day" value="{{ $shippingSameDay }}" required class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech">
                </div>
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Free Shipping Threshold (₦)</label>
                    <input type="number" name="free_shipping_threshold" value="{{ $freeShippingThreshold }}" required class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Top Promotional Announcement Bar Text</label>
                    <input type="text" name="announcement_text" value="{{ $announcementText }}" required class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5">
                </div>
                <div>
                    <label class="block font-mono-tech text-[11px] uppercase mb-1">Official Store Contact Email *</label>
                    <input type="email" name="contact_email" value="{{ $contactEmail ?? 'SAVAGEXO@GMAIL.COM' }}" required class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech font-bold">
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="cod_enabled" value="1" {{ $codEnabled == '1' ? 'checked' : '' }} class="accent-[#0E0E0E]">
                    <span class="font-bold uppercase">Enable Cash on Delivery (COD) at Checkout</span>
                </label>

                <button type="submit" class="btn-savage-primary px-8 py-3.5 font-bold uppercase tracking-[0.2em]">
                    Save Store Configuration
                </button>
            </div>
        </form>

        <!-- 2. Homepage Banners Management -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-5">
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#0E0E0E] p-6 space-y-4 text-xs">
                    @csrf
                    <h2 class="font-editorial text-2xl font-bold border-b border-[#E2DDD3] pb-2">Add Campaign Banner</h2>

                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1">Headline Title *</label>
                        <input type="text" name="title" required placeholder="THE HARMATTAN ARCHIVE" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1">Eyebrow Label</label>
                            <input type="text" name="eyebrow" value="COLLECTION 05" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5">
                        </div>
                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1">Placement</label>
                            <select name="position" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3 py-2.5">
                                <option value="hero">Hero Main</option>
                                <option value="sneaker_hero">Sneaker Hero Banner</option>
                                <option value="promo_primary">Editorial Promo</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1">CTA Button Text *</label>
                            <input type="text" name="cta_text" value="Explore Collection" required class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5">
                        </div>
                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1">CTA Link *</label>
                            <input type="text" name="cta_link" value="/shop" required class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2.5 font-mono-tech">
                        </div>
                    </div>

                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1">Image Path or Upload</label>
                        <input type="text" name="image_path" value="/images/banners/hero-main.jpg" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3.5 py-2 font-mono-tech mb-2">
                        <input type="file" name="image_file" accept="image/*" class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-3 py-2">
                    </div>

                    <button type="submit" class="w-full btn-savage-primary py-3.5 font-bold uppercase tracking-[0.2em]">
                        Publish Banner
                    </button>
                </form>
            </div>

            <div class="lg:col-span-7 space-y-4">
                @foreach($banners as $banner)
                    <div class="bg-white border border-[#E2DDD3] p-5 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                        <div class="flex items-center gap-4">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-24 h-16 object-cover border border-[#E2DDD3]">
                            <div>
                                <span class="font-mono-tech text-[10px] uppercase text-[#B85435]">{{ strtoupper($banner->position) }}</span>
                                <h3 class="font-bold text-sm">{{ $banner->title }}</h3>
                                <p class="text-neutral-500 line-clamp-1">{{ $banner->description }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="font-mono-tech text-[11px] uppercase px-3 py-1 border border-[#0E0E0E]">
                                    {{ $banner->is_active ? 'Active' : 'Hidden' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.banners.delete', $banner) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-mono-tech text-[11px] uppercase text-red-700 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.admin>
