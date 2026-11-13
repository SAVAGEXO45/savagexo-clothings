<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — SAVAGEXO Admin' : 'SAVAGEXO CLOTHINGS — Atelier Executive Console' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F4F1EA] text-[#0E0E0E] flex flex-col lg:flex-row" x-data="{ sidebarOpen: false }">
    <!-- Left Executive Sidebar -->
    <aside class="w-full lg:w-72 bg-[#0E0E0E] text-[#F7F5F0] flex-shrink-0 flex flex-col justify-between border-r border-white/10">
        <div>
            <!-- Brand Header -->
            <div class="h-20 px-6 flex items-center justify-between border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="block">
                    <span class="font-editorial text-2xl font-bold tracking-[0.22em] uppercase text-white block">SAVAGEXO</span>
                    <span class="font-mono-tech text-[10px] tracking-[0.28em] uppercase text-[#C9A96E] block">ATELIER ADMIN CONSOLE</span>
                </a>
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="square" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav :class="sidebarOpen ? 'block' : 'hidden lg:block'" class="p-5 space-y-1.5 text-xs font-semibold uppercase tracking-[0.15em]">
                <p class="px-3 py-2 text-[10px] font-mono-tech text-neutral-500 uppercase tracking-[0.25em]">Executive Overview</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3.5 py-3 transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#B85435] text-white' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <span>Sales & Analytics</span>
                    <span>→</span>
                </a>

                <p class="px-3 pt-5 pb-2 text-[10px] font-mono-tech text-neutral-500 uppercase tracking-[0.25em]">Catalog & Inventory</p>
                <a href="{{ route('admin.products') }}" class="flex items-center justify-between px-3.5 py-3 transition {{ request()->routeIs('admin.products*') ? 'bg-[#B85435] text-white' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <span>Products & Stock</span>
                    <span>→</span>
                </a>
                <a href="{{ route('admin.brands') }}" class="flex items-center justify-between px-3.5 py-3 transition {{ request()->routeIs('admin.brands*') ? 'bg-[#B85435] text-white' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <span>Sneaker Brands</span>
                    <span>→</span>
                </a>
                <a href="{{ route('admin.categories') }}" class="flex items-center justify-between px-3.5 py-3 transition {{ request()->routeIs('admin.categories*') ? 'bg-[#B85435] text-white' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <span>Categories</span>
                    <span>→</span>
                </a>

                <p class="px-3 pt-5 pb-2 text-[10px] font-mono-tech text-neutral-500 uppercase tracking-[0.25em]">Fulfillment & Clients</p>
                <a href="{{ route('admin.orders') }}" class="flex items-center justify-between px-3.5 py-3 transition {{ request()->routeIs('admin.orders*') ? 'bg-[#B85435] text-white' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <span>Orders & Dispatch</span>
                    <span>→</span>
                </a>
                <a href="{{ route('admin.customers') }}" class="flex items-center justify-between px-3.5 py-3 transition {{ request()->routeIs('admin.customers*') ? 'bg-[#B85435] text-white' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <span>Customers</span>
                    <span>→</span>
                </a>
                <a href="{{ route('admin.coupons') }}" class="flex items-center justify-between px-3.5 py-3 transition {{ request()->routeIs('admin.coupons*') ? 'bg-[#B85435] text-white' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <span>Discount Coupons</span>
                    <span>→</span>
                </a>
                <a href="{{ route('admin.reviews') }}" class="flex items-center justify-between px-3.5 py-3 transition {{ request()->routeIs('admin.reviews*') ? 'bg-[#B85435] text-white' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <span>Client Reviews</span>
                    <span>→</span>
                </a>
                <a href="{{ route('admin.banners') }}" class="flex items-center justify-between px-3.5 py-3 transition {{ request()->routeIs('admin.banners*') ? 'bg-[#B85435] text-white' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <span>Banners & Shipping</span>
                    <span>→</span>
                </a>
            </nav>
        </div>

        <div class="p-5 border-t border-white/10 space-y-3">
            <div class="px-2 py-1.5 bg-white/5 border border-white/10 text-center">
                <span class="block font-mono-tech text-[9px] uppercase tracking-widest text-neutral-400">SUPPORT DESK</span>
                <a href="mailto:SAVAGEXO@GMAIL.COM" class="font-mono-tech text-[11px] font-bold text-[#C9A96E] hover:underline">SAVAGEXO@GMAIL.COM</a>
            </div>
            <a href="{{ route('home') }}" class="block w-full text-center py-2.5 border border-[#C9A96E] text-[#C9A96E] text-xs uppercase tracking-[0.18em] hover:bg-[#C9A96E] hover:text-[#0E0E0E] transition">
                View Live Storefront ↗
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 text-xs uppercase tracking-widest text-neutral-400 hover:text-white">
                    Sign Out ({{ auth()->user()->name }})
                </button>
            </form>
        </div>
    </aside>

    <!-- Right Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Bar -->
        <header class="h-20 bg-[#F7F5F0] border-b border-[#E2DDD3] px-6 sm:px-10 flex items-center justify-between">
            <div>
                <h1 class="font-editorial text-2xl sm:text-3xl font-bold tracking-wide text-[#0E0E0E]">
                    {{ $heading ?? 'Executive Command Center' }}
                </h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.products.create') }}" class="btn-savage-primary px-4 py-2.5 text-xs font-bold uppercase tracking-[0.16em]">
                    + New Garment
                </a>
            </div>
        </header>

        @if(session('success'))
            <div class="bg-[#0E0E0E] text-[#F7F5F0] px-6 sm:px-10 py-3 text-xs tracking-wider border-b border-[#C9A96E]/30 flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-800 text-white px-6 sm:px-10 py-3 text-xs tracking-wider">
                <span>⚠ {{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-b border-red-300 text-red-900 px-6 sm:px-10 py-3 text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <main class="p-6 sm:p-10 flex-1">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
