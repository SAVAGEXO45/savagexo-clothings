<x-layouts.app title="Sign In">
    <section class="max-w-5xl mx-auto px-4 sm:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 border border-[#0E0E0E] bg-[#EFECE6] overflow-hidden shadow-xl">
            <!-- Left Editorial Image Panel -->
            <div class="lg:col-span-5 relative min-h-[300px] bg-[#0E0E0E] text-white p-8 flex flex-col justify-between">
                <img src="/images/products/mens-tailored-coat.jpg" alt="SAVAGEXO Atelier" class="absolute inset-0 w-full h-full object-cover opacity-55">
                <div class="relative z-10">
                    <span class="font-mono-tech text-[10px] uppercase tracking-[0.28em] text-[#C9A96E]">PRIVATE CLIENT PORTAL</span>
                    <h2 class="font-editorial text-3xl font-bold mt-2">SAVAGEXO CLOTHINGS</h2>
                </div>
                <div class="relative z-10 space-y-2 text-xs text-neutral-300">
                    <p>Access your order history, live courier tracking, saved wishlist, and Victoria Island concierge appointments.</p>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="lg:col-span-7 p-8 sm:p-12 bg-[#F7F5F0] space-y-6">
                <div>
                    <p class="font-mono-tech text-xs uppercase tracking-[0.22em] text-[#B85435]">WELCOME BACK</p>
                    <h1 class="font-editorial text-3xl sm:text-4xl font-bold mt-1">Sign In to Your Account</h1>
                </div>

                @if($errors->any())
                    <div class="p-4 bg-red-50 border border-red-300 text-red-900 text-xs">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="patron@example.com"
                            class="w-full bg-white border border-[#0E0E0E] px-4 py-3.5 text-xs focus:outline-none focus:border-[#B85435]"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-[11px] font-mono-tech uppercase tracking-wider">Password</label>
                            <a href="{{ route('password.request') }}" class="text-[11px] font-mono-tech text-[#B85435] hover:underline">Forgot Password?</a>
                        </div>
                        <input
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full bg-white border border-[#0E0E0E] px-4 py-3.5 text-xs focus:outline-none focus:border-[#B85435]"
                        >
                    </div>

                    <label class="inline-flex items-center gap-2 text-xs cursor-pointer">
                        <input type="checkbox" name="remember" class="accent-[#0E0E0E]">
                        <span>Keep me signed in on this device</span>
                    </label>

                    <button type="submit" class="w-full btn-savage-primary py-4 text-xs font-bold uppercase tracking-[0.22em]">
                        Sign In →
                    </button>
                </form>

                <!-- Quick Demo Credentials Helper for Local Testing -->
                <div class="p-4 bg-[#EFECE6] border border-[#D5CFC2] text-[11px] font-mono-tech space-y-1">
                    <p class="font-bold text-[#0E0E0E] uppercase">Local Development Demo Credentials:</p>
                    <p>Admin: <strong>admin@savagexo.com</strong> / <strong>SavageAdmin@2026</strong></p>
                    <p>Customer: <strong>amara.okafor@gmail.com</strong> / <strong>Customer@2026</strong></p>
                </div>

                <p class="text-xs text-neutral-600 pt-2 border-t border-[#E2DDD3]">
                    New to SAVAGEXO CLOTHINGS?
                    <a href="{{ route('register') }}" class="font-bold uppercase tracking-wider text-[#B85435] hover:underline ml-1">Create a Client Profile →</a>
                </p>
            </div>
        </div>
    </section>
</x-layouts.app>
