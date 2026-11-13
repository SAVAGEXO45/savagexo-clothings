<aside class="lg:col-span-3 space-y-6">
    <div class="bg-[#0E0E0E] text-[#F7F5F0] p-6 space-y-2">
        <p class="font-mono-tech text-[10px] uppercase tracking-[0.25em] text-[#C9A96E]">PRIVATE CLIENT PORTAL</p>
        <h2 class="font-editorial text-2xl font-bold text-white">{{ auth()->user()->name }}</h2>
        <p class="text-xs text-neutral-400 truncate">{{ auth()->user()->email }}</p>
    </div>

    <nav class="bg-[#EFECE6] border border-[#E2DDD3] divide-y divide-[#D5CFC2] text-xs font-bold uppercase tracking-[0.16em]">
        <a href="{{ route('account.dashboard') }}" class="flex items-center justify-between px-5 py-4 transition {{ request()->routeIs('account.dashboard') ? 'bg-[#0E0E0E] text-white' : 'hover:bg-white' }}">
            <span>Client Overview</span>
            <span>→</span>
        </a>
        <a href="{{ route('account.orders') }}" class="flex items-center justify-between px-5 py-4 transition {{ request()->routeIs('account.orders*') ? 'bg-[#0E0E0E] text-white' : 'hover:bg-white' }}">
            <span>Order History & Tracking</span>
            <span>→</span>
        </a>
        <a href="{{ route('account.wishlist') }}" class="flex items-center justify-between px-5 py-4 transition {{ request()->routeIs('account.wishlist') ? 'bg-[#0E0E0E] text-white' : 'hover:bg-white' }}">
            <span>Saved Wishlist</span>
            <span>→</span>
        </a>
        <a href="{{ route('account.addresses') }}" class="flex items-center justify-between px-5 py-4 transition {{ request()->routeIs('account.addresses') ? 'bg-[#0E0E0E] text-white' : 'hover:bg-white' }}">
            <span>Address Book</span>
            <span>→</span>
        </a>
        <a href="{{ route('account.profile') }}" class="flex items-center justify-between px-5 py-4 transition {{ request()->routeIs('account.profile') ? 'bg-[#0E0E0E] text-white' : 'hover:bg-white' }}">
            <span>Profile & Security</span>
            <span>→</span>
        </a>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-5 py-4 bg-[#B85435] text-white">
                <span>Atelier Admin Console</span>
                <span>↗</span>
            </a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-5 py-4 text-xs font-bold uppercase tracking-[0.16em] text-red-700 hover:bg-white">
                Sign Out
            </button>
        </form>
    </nav>
</aside>
