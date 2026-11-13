<x-layouts.app title="Create Client Profile">
    <section class="max-w-5xl mx-auto px-4 sm:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 border border-[#0E0E0E] bg-[#EFECE6] overflow-hidden shadow-xl">
            <!-- Left Editorial Image Panel -->
            <div class="lg:col-span-5 relative min-h-[320px] bg-[#0E0E0E] text-white p-8 flex flex-col justify-between">
                <img src="/images/products/womens-silk-drape.jpg" alt="SAVAGEXO Couture" class="absolute inset-0 w-full h-full object-cover opacity-55">
                <div class="relative z-10">
                    <span class="font-mono-tech text-[10px] uppercase tracking-[0.28em] text-[#C9A96E]">JOIN THE MAISON</span>
                    <h2 class="font-editorial text-3xl font-bold mt-2">PRIVATE CLIENT MEMBERSHIP</h2>
                </div>
                <div class="relative z-10 space-y-2 text-xs text-neutral-300">
                    <p>Enjoy priority access to seasonal releases, seamless checkout, saved delivery addresses, and order tracking.</p>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="lg:col-span-7 p-8 sm:p-12 bg-[#F7F5F0] space-y-6">
                <div>
                    <p class="font-mono-tech text-xs uppercase tracking-[0.22em] text-[#B85435]">NEW REGISTRATION</p>
                    <h1 class="font-editorial text-3xl sm:text-4xl font-bold mt-1">Create Your Profile</h1>
                </div>

                @if($errors->any())
                    <div class="p-4 bg-red-50 border border-red-300 text-red-900 text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">Full Name *</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Amara Okafor"
                            class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">Email Address *</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="amara@example.com"
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">Phone Number (Nigeria / Intl)</label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="+234 803 000 0000"
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">Password (Min. 8 chars) *</label>
                            <input
                                type="password"
                                name="password"
                                required
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                        <div>
                            <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">Confirm Password *</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                required
                                class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                            >
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-savage-primary py-4 text-xs font-bold uppercase tracking-[0.22em] mt-2">
                        Register Client Profile →
                    </button>
                </form>

                <p class="text-xs text-neutral-600 pt-2 border-t border-[#E2DDD3]">
                    Already registered?
                    <a href="{{ route('login') }}" class="font-bold uppercase tracking-wider text-[#B85435] hover:underline ml-1">Sign In →</a>
                </p>
            </div>
        </div>
    </section>
</x-layouts.app>
