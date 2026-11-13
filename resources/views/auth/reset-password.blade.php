<x-layouts.app title="Set New Password">
    <section class="max-w-lg mx-auto px-4 py-16">
        <div class="bg-[#EFECE6] border border-[#0E0E0E] p-8 sm:p-10 space-y-6">
            <div>
                <p class="font-mono-tech text-xs uppercase tracking-[0.22em] text-[#B85435]">SECURITY CREDENTIALS</p>
                <h1 class="font-editorial text-3xl font-bold mt-1">Choose a New Password</h1>
            </div>

            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-300 text-red-900 text-xs">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        required
                        class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                    >
                </div>

                <div>
                    <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">New Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                    >
                </div>

                <div>
                    <label class="block text-[11px] font-mono-tech uppercase tracking-wider mb-1">Confirm New Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full bg-white border border-[#0E0E0E] px-4 py-3 text-xs"
                    >
                </div>

                <button type="submit" class="w-full btn-savage-primary py-4 text-xs font-bold uppercase tracking-[0.2em]">
                    Update Password
                </button>
            </form>
        </div>
    </section>
</x-layouts.app>
