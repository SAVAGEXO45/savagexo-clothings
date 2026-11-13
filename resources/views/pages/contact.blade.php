<x-layouts.app title="Contact & Client Support — SAVAGEXO@GMAIL.COM">
    <!-- Hero Header -->
    <section class="bg-[#0E0E0E] text-[#F7F5F0] py-16 border-b border-[#E2DDD3]">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8">
            <p class="font-mono-tech text-xs uppercase tracking-[0.25em] text-[#C9A96E]">
                CLIENT CONCIERGE & CUSTOMER SUPPORT
            </p>
            <h1 class="font-editorial text-4xl sm:text-6xl font-bold text-white mt-2">
                Contact SAVAGEXO CLOTHINGS
            </h1>
            <p class="text-sm sm:text-base text-neutral-300 max-w-2xl mt-3">
                Direct assistance for bespoke tailoring appointments, sneaker sizing inquiries, nationwide delivery tracking, and wholesale partnerships.
            </p>
        </div>
    </section>

    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left: Direct Contact Channels -->
            <div class="lg:col-span-5 space-y-8">
                <div class="bg-[#0E0E0E] text-[#F7F5F0] p-8 border border-[#0E0E0E] space-y-4">
                    <span class="font-mono-tech text-[10px] uppercase tracking-[0.25em] text-[#C9A96E]">OFFICIAL CONTACT EMAIL</span>
                    <h2 class="font-editorial text-3xl font-bold text-white">Direct Electronic Desk</h2>
                    <p class="text-xs text-neutral-300 leading-relaxed">
                        For all customer support, order modifications, return authorizations, and sneaker sourcing requests, reach our team directly at:
                    </p>
                    <a
                        href="mailto:SAVAGEXO@GMAIL.COM"
                        class="inline-block font-mono-tech text-lg sm:text-xl font-bold text-[#C9A96E] hover:text-white underline tracking-wider"
                    >
                        SAVAGEXO@GMAIL.COM
                    </a>
                    <p class="font-mono-tech text-[11px] text-neutral-400 pt-2">
                        Average response time: Under 2 hours (Monday – Saturday, 9:00 AM – 8:00 PM WAT)
                    </p>
                </div>

                <div class="bg-[#EFECE6] border border-[#E2DDD3] p-8 space-y-6 text-xs">
                    <div>
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">LAGOS FLAGSHIP ATELIER & SNEAKER VAULT</p>
                        <h3 class="font-editorial text-2xl font-bold mt-1">Victoria Island Showroom</h3>
                        <p class="text-neutral-600 mt-1 leading-relaxed">
                            14 Akin Adesola Street, Victoria Island, Lagos, Nigeria<br>
                            Tel: +234 (0) 908 728 2439<br>
                            Email: <a href="mailto:SAVAGEXO@GMAIL.COM" class="font-mono-tech font-bold underline">SAVAGEXO@GMAIL.COM</a>
                        </p>
                    </div>

                    <div class="border-t border-[#D5CFC2] pt-5">
                        <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">ABUJA PRIVATE LOUNGE</p>
                        <h3 class="font-editorial text-2xl font-bold mt-1">Maitama Client Suite</h3>
                        <p class="text-neutral-600 mt-1 leading-relaxed">
                            22 Gana Street, Maitama, Abuja FCT, Nigeria<br>
                            Email: <a href="mailto:SAVAGEXO@GMAIL.COM" class="font-mono-tech font-bold underline">SAVAGEXO@GMAIL.COM</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right: Customer Support Inquiry Form -->
            <div class="lg:col-span-7">
                <form action="{{ route('pages.contact.submit') }}" method="POST" class="bg-white border border-[#0E0E0E] p-8 sm:p-10 space-y-6 text-xs">
                    @csrf
                    <div class="border-b border-[#E2DDD3] pb-4">
                        <p class="font-mono-tech text-[10px] uppercase tracking-[0.25em] text-[#B85435]">SEND A DIRECT MESSAGE</p>
                        <h2 class="font-editorial text-3xl font-bold mt-1">Customer Support & Concierge Form</h2>
                        <p class="text-neutral-500 mt-1">Messages are routed directly to <strong>SAVAGEXO@GMAIL.COM</strong>.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Full Name *</label>
                            <input
                                type="text"
                                name="name"
                                required
                                value="{{ old('name', auth()->user()?->name) }}"
                                placeholder="Chukwudi Okonkwo"
                                class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3"
                            >
                        </div>
                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Your Email Address *</label>
                            <input
                                type="email"
                                name="email"
                                required
                                value="{{ old('email', auth()->user()?->email) }}"
                                placeholder="patron@example.com"
                                class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Phone Number (Optional)</label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', auth()->user()?->phone) }}"
                                placeholder="+234 803 000 0000"
                                class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3"
                            >
                        </div>
                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1.5">Subject *</label>
                            <input
                                type="text"
                                name="subject"
                                required
                                value="{{ old('subject') }}"
                                placeholder="Order Tracking / Sneaker Sizing / Bespoke Fit"
                                class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block font-mono-tech text-[11px] uppercase mb-1.5">How Can We Assist You? *</label>
                        <textarea
                            name="message"
                            rows="5"
                            required
                            placeholder="Include your order number (e.g., SXO-2026-...) or sneaker model/size inquiry..."
                            class="w-full bg-[#F7F5F0] border border-[#0E0E0E] px-4 py-3"
                        >{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn-savage-primary px-10 py-4 text-xs font-bold uppercase tracking-[0.22em]">
                        Transmit Message to SAVAGEXO@GMAIL.COM
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-layouts.app>
