<x-layouts.app title="Forgot Password">
    <section class="max-w-lg mx-auto px-4 py-16">
        <div class="bg-[#EFECE6] border border-[#0E0E0E] p-8 sm:p-10 space-y-6">
            <div>
                <p class="font-mono-tech text-xs uppercase tracking-[0.22em] text-[#B85435]">ACCOUNT RECOVERY</p>
                <h1 class="font-editorial text-3xl font-bold mt-1">Reset Your Password</h1>
                <p class="text-xs text-neutral-600 mt-2">Enter your registered email address and we will generate a secure password reset link.</p>
            </div>

            @if(session('dev_reset_link'))
                <div class="p-4 bg-white border border-[#0E0E0E] text-xs space-y-2">
                    <p class="font-mono-tech text-[10px] uppercase tracking-widest text-[#B85435]">LOCAL DEV RESET LINK GENERATED:</p>
                    <a href="{{ session('dev_reset_link') }}" class="block font-mono-tech text-xs underline break-all text-[#0E0E0E] hover:text-[#B85435]">
                        Click here to reset your password →
                    </a>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1.5">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        required
                        placeholder="Enter your email..."
                        class="w-full bg-white border border-[#0E0E0E] px-4 py-3.5 text-xs"
                    >
                </div>
                <button type="submit" class="w-full btn-savage-primary py-4 text-xs font-bold uppercase tracking-[0.2em]">
                    Send Password Reset Link
                </button>
            </form>

            <div class="text-center pt-2">
                <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest hover:text-[#B85435]">← Back to Sign In</a>
            </div>
        </div>
    </section>
</x-layouts.app>
