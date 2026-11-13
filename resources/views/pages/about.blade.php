<x-layouts.app title="About the Maison — SAVAGEXO CLOTHINGS">
    <!-- Hero Header -->
    <section class="bg-[#0E0E0E] text-[#F7F5F0] py-20 border-b border-[#E2DDD3]">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#C9A96E]">
                    LAGOS • LONDON • MILAN • GLOBAL SNEAKER VAULT
                </p>
                <h1 class="font-editorial text-4xl sm:text-6xl lg:text-7xl font-bold text-white leading-[0.95]">
                    Architectural Luxury Meets Global Sneaker Culture.
                </h1>
                <p class="text-sm sm:text-base text-neutral-300 leading-relaxed max-w-2xl">
                    Founded in Victoria Island, Lagos, <strong>SAVAGEXO CLOTHINGS</strong> bridges contemporary West African sartorial craftsmanship—heavyweight wool Agbadas, raw mulberry silk drapes, and Japanese selvedge denim—with a multi-brand sneaker vault featuring the world's most iconic footwear silhouettes.
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a href="{{ route('shop.index') }}" class="btn-savage-accent px-8 py-4 text-xs font-bold uppercase tracking-[0.2em]">
                        Explore Ready-to-Wear
                    </a>
                    <a href="{{ route('shop.sneakers') }}" class="px-8 py-4 border border-white/30 text-white text-xs font-bold uppercase tracking-[0.2em] hover:bg-white hover:text-[#0E0E0E] transition">
                        Explore Sneaker Vault
                    </a>
                </div>
            </div>
            <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                <img src="/images/banners/hero-main.jpg" alt="SAVAGEXO Atelier" class="w-full aspect-[4/5] object-cover border border-white/15">
                <img src="/images/sneakers/nike-air-force-1-07-lateral.jpg" alt="SAVAGEXO Sneaker Curation" class="w-full aspect-[4/5] object-cover border border-white/15 mt-8">
            </div>
        </div>
    </section>

    <!-- Pillars -->
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-[#EFECE6] border border-[#E2DDD3] p-8 space-y-3">
                <span class="font-mono-tech text-xs uppercase tracking-widest text-[#B85435]">01 // SARTORIAL HERITAGE</span>
                <h2 class="font-editorial text-3xl font-bold">Multi-Angle Craftsmanship</h2>
                <p class="text-xs text-neutral-600 leading-relaxed">
                    Every SAVAGEXO garment is photographed across 6 distinct studio angles—front, back, profile, macro fabric detail, lookbook drape, and alternate colourways—so patrons experience the textile weight before dispatch.
                </p>
            </div>
            <div class="bg-[#EFECE6] border border-[#E2DDD3] p-8 space-y-3">
                <span class="font-mono-tech text-xs uppercase tracking-widest text-[#B85435]">02 // THE SNEAKER VAULT</span>
                <h2 class="font-editorial text-3xl font-bold">15+ Global Footwear Brands</h2>
                <p class="text-xs text-neutral-600 leading-relaxed">
                    From Nike Air Force 1, Dunk Low, and Air Jordan 1 to Adidas Samba OG, New Balance 9060, Puma Palermo, Asics Gel-Kayano 14, and Salomon XT-6, our sneaker archive is independently curated for discerning collectors.
                </p>
            </div>
            <div class="bg-[#EFECE6] border border-[#E2DDD3] p-8 space-y-3">
                <span class="font-mono-tech text-xs uppercase tracking-widest text-[#B85435]">03 // DIRECT CLIENT CONCIERGE</span>
                <h2 class="font-editorial text-3xl font-bold">Always Reachable</h2>
                <p class="text-xs text-neutral-600 leading-relaxed">
                    Whether you require same-day delivery in Lagos, DHL Express dispatch to Abuja or Port Harcourt, or sizing advice, contact our concierge directly at <a href="mailto:SAVAGEXO@GMAIL.COM" class="font-mono-tech font-bold text-[#0E0E0E] underline">SAVAGEXO@GMAIL.COM</a>.
                </p>
            </div>
        </div>
    </section>
</x-layouts.app>
