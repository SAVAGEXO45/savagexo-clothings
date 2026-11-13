<x-layouts.app title="Profile & Security">
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            @include('account.partials.sidebar')

            <div class="lg:col-span-9 space-y-10">
                <div class="border-b border-[#E2DDD3] pb-4">
                    <p class="font-mono-tech text-xs uppercase tracking-[0.22em] text-[#B85435]">PERSONAL DETAILS</p>
                    <h1 class="font-editorial text-3xl sm:text-4xl font-bold mt-1">Profile & Security Settings</h1>
                </div>

                @if($errors->any())
                    <div class="p-4 bg-red-50 border border-red-300 text-red-900 text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Update Profile Info -->
                    <form action="{{ route('account.profile.update') }}" method="POST" class="bg-[#EFECE6] border border-[#0E0E0E] p-7 space-y-4 text-xs">
                        @csrf
                        @method('PUT')
                        <h2 class="font-editorial text-2xl font-bold border-b border-[#D5CFC2] pb-2">Client Profile</h2>

                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-white border border-[#0E0E0E] px-3.5 py-3">
                        </div>

                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-white border border-[#0E0E0E] px-3.5 py-3">
                        </div>

                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-3">
                        </div>

                        <button type="submit" class="btn-savage-primary px-6 py-3.5 font-bold uppercase tracking-[0.2em]">
                            Save Changes
                        </button>
                    </form>

                    <!-- Update Password -->
                    <form action="{{ route('account.profile.password') }}" method="POST" class="bg-[#EFECE6] border border-[#0E0E0E] p-7 space-y-4 text-xs">
                        @csrf
                        @method('PUT')
                        <h2 class="font-editorial text-2xl font-bold border-b border-[#D5CFC2] pb-2">Change Password</h2>

                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1">Current Password</label>
                            <input type="password" name="current_password" required class="w-full bg-white border border-[#0E0E0E] px-3.5 py-3">
                        </div>

                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1">New Password</label>
                            <input type="password" name="password" required class="w-full bg-white border border-[#0E0E0E] px-3.5 py-3">
                        </div>

                        <div>
                            <label class="block font-mono-tech text-[11px] uppercase mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required class="w-full bg-white border border-[#0E0E0E] px-3.5 py-3">
                        </div>

                        <button type="submit" class="btn-savage-primary px-6 py-3.5 font-bold uppercase tracking-[0.2em]">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
