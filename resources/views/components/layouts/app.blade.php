<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — SAVAGEXO CLOTHINGS' : 'SAVAGEXO CLOTHINGS — Luxury Fashion & Sneaker Destination | Lagos • London • Milan' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'SAVAGEXO CLOTHINGS is a premier fashion and sneaker destination offering Nike, Adidas, Puma, New Balance, Jordan, and bespoke Nigerian luxury ready-to-wear.' }}">

    <!-- Editorial Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    x-data="{
        mobileMenuOpen: false,
        searchModalOpen: false,
        searchQuery: '',
        searchResults: [],
        searchLoading: false,
        toasts: [],
        addToast(message, type = 'success') {
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message, type });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 4200);
        },
        async performLiveSearch() {
            if (this.searchQuery.trim().length < 2) {
                this.searchResults = [];
                return;
            }
            this.searchLoading = true;
            try {
                const res = await fetch('/api/products/search?q=' + encodeURIComponent(this.searchQuery));
                const json = await res.json();
                this.searchResults = json.data || [];
            } catch (e) {
                this.searchResults = [];
            } finally {
                this.searchLoading = false;
            }
        }
    }"
    @savage-toast.window="addToast($event.detail.message, $event.detail.type)"
    class="min-h-screen flex flex-col bg-[#F7F5F0] text-[#0E0E0E] selection:bg-[#B85435] selection:text-white"
>
    <!-- 1. PROMOTIONAL ANNOUNCEMENT BAR -->
    <div class="bg-[#0E0E0E] text-[#F7F5F0] border-b border-white/10 text-[11px] tracking-[0.2em] uppercase py-2.5 px-4">
        <div class="max-w-[1440px] mx-auto flex items-center justify-between gap-4">
            <a href="mailto:SAVAGEXO@GMAIL.COM" class="hidden lg:inline-block text-[#C9A96E] font-mono-tech hover:underline">
                SAVAGEXO@GMAIL.COM
            </a>
            <p class="text-center mx-auto font-medium truncate">
                {{ $globalAnnouncementText ?? 'LAGOS • LONDON • MILAN — COMPLIMENTARY EXPRESS DELIVERY ON ORDERS OVER ₦250,000 | USE CODE: SAVAGE15' }}
            </p>
            <div class="hidden md:flex items-center gap-5 text-[10px] text-neutral-400">
                <a href="{{ route('orders.track') }}" class="hover:text-white transition">Track Order</a>
                <span>•</span>
                <a href="{{ route('pages.contact') }}" class="text-[#C9A96E] hover:underline">Support</a>
            </div>
        </div>
    </div>

    <!-- 2. EDITORIAL NAVIGATION BAR -->
    <header class="sticky top-0 z-40 bg-[#F7F5F0]/95 backdrop-blur-md border-b border-[#E2DDD3]">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 h-20 flex items-center justify-between gap-6">
            <!-- Left: Mobile Hamburger + Desktop Primary Links -->
            <div class="flex items-center gap-7">
                <button
                    type="button"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden p-2 -ml-2 text-[#0E0E0E] hover:text-[#B85435] transition"
                    aria-label="Toggle Menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="square" d="M3 6h18M3 12h18M3 18h12"/>
                    </svg>
                </button>

                <nav class="hidden lg:flex items-center gap-6 text-xs font-semibold tracking-[0.15em] uppercase">
                    <a href="{{ route('home') }}" class="py-1 transition {{ request()->routeIs('home') ? 'text-[#B85435] border-b border-[#B85435]' : 'text-[#0E0E0E] hover:text-[#B85435]' }}">
                        Maison
                    </a>
                    <a href="{{ route('shop.sneakers') }}" class="py-1 transition flex items-center gap-1.5 {{ request()->routeIs('shop.sneakers') ? 'text-[#B85435] border-b border-[#B85435]' : 'text-[#0E0E0E] hover:text-[#B85435]' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#B85435]"></span>
                        <span>Sneakers</span>
                    </a>
                    <a href="{{ route('shop.index') }}" class="py-1 transition {{ request()->routeIs('shop.index') && !request('gender') && !request('category') ? 'text-[#B85435] border-b border-[#B85435]' : 'text-[#0E0E0E] hover:text-[#B85435]' }}">
                        Clothing
                    </a>
                    <a href="{{ route('shop.index', ['gender' => 'men']) }}" class="py-1 transition {{ request('gender') === 'men' ? 'text-[#B85435] border-b border-[#B85435]' : 'text-[#0E0E0E] hover:text-[#B85435]' }}">
                        Men
                    </a>
                    <a href="{{ route('shop.index', ['gender' => 'women']) }}" class="py-1 transition {{ request('gender') === 'women' ? 'text-[#B85435] border-b border-[#B85435]' : 'text-[#0E0E0E] hover:text-[#B85435]' }}">
                        Women
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'traditional-wear']) }}" class="py-1 transition {{ request('category') === 'traditional-wear' ? 'text-[#B85435] border-b border-[#B85435]' : 'text-[#0E0E0E] hover:text-[#B85435]' }}">
                        Traditional
                    </a>
                    <a href="{{ route('pages.contact') }}" class="py-1 transition {{ request()->routeIs('pages.contact') ? 'text-[#B85435] border-b border-[#B85435]' : 'text-[#0E0E0E] hover:text-[#B85435]' }}">
                        Contact
                    </a>
                </nav>
            </div>

            <!-- Center: Brand Wordmark -->
            <a href="{{ route('home') }}" class="text-center group">
                <span class="block font-editorial text-2xl sm:text-3xl font-bold tracking-[0.22em] uppercase text-[#0E0E0E] group-hover:text-[#B85435] transition">
                    SAVAGEXO
                </span>
                <span class="block text-[9px] tracking-[0.38em] uppercase text-neutral-500 -mt-1">
                    CLOTHINGS & SNEAKERS
                </span>
            </a>

            <!-- Right: Search, Wishlist, Account, Bag -->
            <div class="flex items-center gap-4 sm:gap-6">
                <!-- Search Trigger -->
                <button
                    type="button"
                    @click="searchModalOpen = true; $nextTick(() => $refs.globalSearchInput?.focus())"
                    class="flex items-center gap-2 text-xs tracking-[0.14em] uppercase font-medium text-[#0E0E0E] hover:text-[#B85435] transition"
                    title="Search Catalog"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path stroke-linecap="square" d="m20 20-3.5-3.5"></path>
                    </svg>
                    <span class="hidden xl:inline">Search</span>
                </button>

                <!-- Wishlist Link -->
                <a
                    href="{{ auth()->check() ? route('account.wishlist') : route('login') }}"
                    class="relative text-[#0E0E0E] hover:text-[#B85435] transition"
                    title="Saved Wishlist"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span
                        data-wishlist-count
                        class="absolute -top-2 -right-2.5 min-w-[18px] h-[18px] px-1 rounded-full bg-[#EFECE6] border border-[#0E0E0E] text-[#0E0E0E] text-[10px] font-mono-tech font-semibold flex items-center justify-center"
                    >
                        {{ $globalWishlistCount ?? 0 }}
                    </span>
                </a>

                <!-- Account / Admin Link -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            @click="open = !open"
                            @click.outside="open = false"
                            class="flex items-center gap-1.5 text-xs tracking-[0.14em] uppercase font-semibold text-[#0E0E0E] hover:text-[#B85435] transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="hidden sm:inline">{{ Str::words(auth()->user()->name, 1, '') }}</span>
                        </button>

                        <div
                            x-cloak
                            x-show="open"
                            x-transition
                            class="absolute right-0 mt-3 w-60 bg-[#F7F5F0] border border-[#0E0E0E] shadow-2xl py-2 z-50"
                        >
                            <div class="px-4 py-2.5 border-b border-[#E2DDD3]">
                                <p class="text-xs font-bold uppercase tracking-wider truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-neutral-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-[#B85435] hover:bg-[#EFECE6]">
                                    Atelier Admin Console →
                                </a>
                            @endif
                            <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-xs uppercase tracking-wider hover:bg-[#EFECE6]">Client Overview</a>
                            <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-xs uppercase tracking-wider hover:bg-[#EFECE6]">Orders & Tracking</a>
                            <a href="{{ route('account.wishlist') }}" class="block px-4 py-2 text-xs uppercase tracking-wider hover:bg-[#EFECE6]">Saved Pieces</a>
                            <a href="{{ route('account.addresses') }}" class="block px-4 py-2 text-xs uppercase tracking-wider hover:bg-[#EFECE6]">Address Book</a>
                            <a href="{{ route('account.profile') }}" class="block px-4 py-2 text-xs uppercase tracking-wider hover:bg-[#EFECE6]">Profile Settings</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-[#E2DDD3] mt-1 pt-1">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs uppercase tracking-wider text-red-700 hover:bg-[#EFECE6]">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="flex items-center gap-1.5 text-xs tracking-[0.14em] uppercase font-semibold text-[#0E0E0E] hover:text-[#B85435] transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="hidden sm:inline">Sign In</span>
                    </a>
                @endauth

                <!-- Shopping Bag Link -->
                <a
                    href="{{ route('cart.index') }}"
                    class="flex items-center gap-2 bg-[#0E0E0E] text-[#F7F5F0] px-3.5 py-2 text-xs tracking-[0.15em] uppercase font-semibold hover:bg-[#B85435] transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span class="hidden sm:inline">Bag</span>
                    <span data-cart-count class="font-mono-tech text-[#C9A96E] font-bold">
                        {{ $globalCartCount ?? 0 }}
                    </span>
                </a>
            </div>
        </div>

        <!-- Mobile Slide-Down Drawer -->
        <div
            x-cloak
            x-show="mobileMenuOpen"
            x-transition
            class="lg:hidden bg-[#F7F5F0] border-t border-[#E2DDD3] px-6 py-6 space-y-4"
        >
            <form action="{{ route('shop.index') }}" method="GET" class="flex border border-[#0E0E0E]">
                <input
                    type="text"
                    name="q"
                    placeholder="Search Nike, Adidas, Agbada, hoodies..."
                    class="w-full px-3 py-2.5 bg-transparent text-sm focus:outline-none"
                >
                <button type="submit" class="px-4 bg-[#0E0E0E] text-white text-xs uppercase tracking-widest">Go</button>
            </form>
            <div class="grid grid-cols-2 gap-3 pt-2 text-xs font-semibold uppercase tracking-[0.15em]">
                <a href="{{ route('home') }}" class="py-2 border-b border-[#E2DDD3]">Maison Home</a>
                <a href="{{ route('shop.sneakers') }}" class="py-2 border-b border-[#E2DDD3] text-[#B85435]">Sneaker Vault</a>
                <a href="{{ route('shop.index') }}" class="py-2 border-b border-[#E2DDD3]">All Products</a>
                <a href="{{ route('shop.index', ['gender' => 'men']) }}" class="py-2 border-b border-[#E2DDD3]">Men's Edit</a>
                <a href="{{ route('shop.index', ['gender' => 'women']) }}" class="py-2 border-b border-[#E2DDD3]">Women's Edit</a>
                <a href="{{ route('shop.index', ['category' => 'traditional-wear']) }}" class="py-2 border-b border-[#E2DDD3]">Traditional Wear</a>
                <a href="{{ route('pages.about') }}" class="py-2 border-b border-[#E2DDD3]">About Maison</a>
                <a href="{{ route('pages.contact') }}" class="py-2 border-b border-[#E2DDD3]">Contact Support</a>
            </div>
        </div>
    </header>

    <!-- 3. INTERACTIVE SEARCH MODAL -->
    <div
        x-cloak
        x-show="searchModalOpen"
        x-transition.opacity
        @keydown.escape.window="searchModalOpen = false"
        class="fixed inset-0 z-50 bg-black/70 backdrop-blur-xs flex items-start justify-center pt-16 px-4"
    >
        <div
            @click.outside="searchModalOpen = false"
            class="w-full max-w-3xl bg-[#F7F5F0] border border-[#0E0E0E] shadow-2xl p-6 sm:p-8"
        >
            <div class="flex items-center justify-between pb-4 border-b border-[#E2DDD3]">
                <span class="text-[11px] font-mono-tech uppercase tracking-[0.25em] text-neutral-500">SEARCH SAVAGEXO ARCHIVE & SNEAKER VAULT</span>
                <button type="button" @click="searchModalOpen = false" class="text-xs uppercase tracking-widest hover:text-[#B85435]">
                    Close [ESC]
                </button>
            </div>

            <form action="{{ route('shop.index') }}" method="GET" class="mt-4 flex gap-3">
                <input
                    x-ref="globalSearchInput"
                    x-model="searchQuery"
                    @input.debounce.200ms="performLiveSearch()"
                    type="text"
                    name="q"
                    placeholder="Search Nike, Adidas, Puma, New Balance, Jordan, Agbada, Silk..."
                    class="flex-1 bg-white border border-[#0E0E0E] px-4 py-3.5 text-sm focus:outline-none focus:border-[#B85435]"
                >
                <button type="submit" class="btn-savage-primary px-6 py-3.5 text-xs font-bold uppercase tracking-[0.2em]">
                    Search
                </button>
            </form>

            <!-- Instant Live Results -->
            <div x-show="searchResults.length > 0" class="mt-6 divide-y divide-[#E2DDD3] max-h-80 overflow-y-auto">
                <template x-for="item in searchResults" :key="item.id">
                    <a :href="item.url" class="flex items-center gap-4 py-3 hover:bg-[#EFECE6] px-2 transition">
                        <img :src="item.image" :alt="item.name" class="w-12 h-16 object-cover bg-[#EFECE6]">
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-mono-tech uppercase tracking-widest text-[#B85435]" x-text="(item.brand || 'SAVAGEXO') + ' // ' + (item.category || '')"></p>
                            <p class="text-sm font-semibold text-[#0E0E0E] truncate" x-text="item.name"></p>
                        </div>
                        <span class="font-mono-tech text-xs font-bold text-[#0E0E0E]" x-text="item.price_formatted"></span>
                    </a>
                </template>
            </div>
        </div>
    </div>

    <!-- 4. SESSION FLASH BANNERS & FLOATING TOAST NOTIFICATIONS -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="bg-[#0E0E0E] text-[#F7F5F0] border-b border-[#C9A96E]/40 px-4 py-3">
            <div class="max-w-[1440px] mx-auto flex items-center justify-between gap-4 text-xs tracking-wider">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-[#C9A96E]"></span>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-neutral-400 hover:text-white uppercase text-[10px] tracking-widest">Dismiss</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" class="bg-[#963E23] text-white px-4 py-3">
            <div class="max-w-[1440px] mx-auto flex items-center justify-between gap-4 text-xs tracking-wider">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-white"></span>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-white/80 hover:text-white uppercase text-[10px] tracking-widest">Dismiss</button>
            </div>
        </div>
    @endif

    <!-- Live JS Toast Stack -->
    <div class="fixed bottom-6 right-6 z-50 space-y-3 max-w-sm w-full pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-transition
                class="pointer-events-auto bg-[#0E0E0E] text-[#F7F5F0] border-l-4 px-5 py-4 shadow-2xl flex items-start justify-between gap-4"
                :class="toast.type === 'error' ? 'border-red-500' : 'border-[#B85435]'"
            >
                <div>
                    <p class="text-[10px] font-mono-tech uppercase tracking-[0.2em] text-[#C9A96E]" x-text="toast.type === 'error' ? 'Notice' : 'SAVAGEXO Bag'"></p>
                    <p class="text-xs mt-1 leading-relaxed" x-text="toast.message"></p>
                </div>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="text-neutral-400 hover:text-white text-xs">✕</button>
            </div>
        </template>
    </div>

    <!-- 5. MAIN PAGE CONTENT -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- 6. EDITORIAL LUXURY FOOTER -->
    <footer class="bg-[#0E0E0E] text-[#F7F5F0] border-t border-white/10 mt-24">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-8 py-16 lg:py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 pb-16 border-b border-white/10">
                <!-- Col 1-2: Brand Manifesto -->
                <div class="lg:col-span-2 space-y-5">
                    <div>
                        <span class="font-editorial text-3xl font-bold tracking-[0.22em] uppercase text-white">SAVAGEXO</span>
                        <p class="text-[10px] tracking-[0.35em] uppercase text-[#C9A96E] mt-1">CLOTHINGS & SNEAKER DESTINATION • EST. LAGOS</p>
                    </div>
                    <p class="text-sm text-neutral-400 leading-relaxed max-w-md">
                        A premier contemporary fashion house and curated sneaker destination uniting Nigerian royal tailoring, heavyweight architectural streetwear, and global sneaker icons from Nike, Adidas, Puma, Jordan, and New Balance.
                    </p>
                    <div class="pt-1 space-y-1 text-xs font-mono-tech">
                        <p class="text-neutral-400">DIRECT CONCIERGE & SUPPORT EMAIL:</p>
                        <a href="mailto:SAVAGEXO@GMAIL.COM" class="text-sm font-bold text-[#C9A96E] hover:underline">
                            SAVAGEXO@GMAIL.COM
                        </a>
                    </div>
                </div>

                <!-- Col 3: Collections & Sneaker Brands -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-[0.22em] text-[#C9A96E]">Sneakers & Apparel</h4>
                    <ul class="space-y-2.5 text-xs text-neutral-300">
                        <li><a href="{{ route('shop.sneakers') }}" class="hover:text-white transition font-semibold text-white">The Sneaker Vault (All Brands)</a></li>
                        <li><a href="{{ route('shop.sneakers', ['brand' => 'nike']) }}" class="hover:text-white transition">Nike & Air Jordan</a></li>
                        <li><a href="{{ route('shop.sneakers', ['brand' => 'adidas']) }}" class="hover:text-white transition">Adidas Originals</a></li>
                        <li><a href="{{ route('shop.sneakers', ['brand' => 'puma']) }}" class="hover:text-white transition">Puma & New Balance</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'traditional-wear']) }}" class="hover:text-white transition">Traditional Royalty & Agbada</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'streetwear']) }}" class="hover:text-white transition">600GSM Streetwear</a></li>
                    </ul>
                </div>

                <!-- Col 4: Client Concierge -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-[0.22em] text-[#C9A96E]">Client Concierge</h4>
                    <ul class="space-y-2.5 text-xs text-neutral-300">
                        <li><a href="{{ route('pages.about') }}" class="hover:text-white transition">About SAVAGEXO</a></li>
                        <li><a href="{{ route('pages.contact') }}" class="hover:text-white transition">Contact & Support</a></li>
                        <li><a href="{{ route('orders.track') }}" class="hover:text-white transition">Track Your Order</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition">Shopping Bag</a></li>
                        <li><a href="{{ route('account.dashboard') }}" class="hover:text-white transition">Private Client Portal</a></li>
                        <li><a href="mailto:SAVAGEXO@GMAIL.COM" class="text-[#C9A96E] hover:underline">SAVAGEXO@GMAIL.COM</a></li>
                    </ul>
                </div>

                <!-- Col 5: Flagship Ateliers -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-[0.22em] text-[#C9A96E]">Flagship Ateliers</h4>
                    <div class="text-xs text-neutral-400 space-y-3 leading-relaxed">
                        <p>
                            <strong class="text-white block uppercase tracking-wider">Lagos Flagship</strong>
                            14 Adeola Odeku Street, Victoria Island, Lagos
                        </p>
                        <p>
                            <strong class="text-white block uppercase tracking-wider">Abuja Showroom</strong>
                            8 Danube Street, Maitama, Abuja
                        </p>
                        <p class="font-mono-tech text-[#C9A96E]">
                            <a href="mailto:SAVAGEXO@GMAIL.COM" class="hover:underline">SAVAGEXO@GMAIL.COM</a><br>
                            +234 901 200 8800
                        </p>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500">
                <p>© {{ date('Y') }} SAVAGEXO CLOTHINGS LTD. Contact: <a href="mailto:SAVAGEXO@GMAIL.COM" class="text-neutral-300 hover:underline">SAVAGEXO@GMAIL.COM</a></p>
                <div class="flex items-center gap-6 uppercase tracking-widest text-[11px]">
                    <a href="https://instagram.com" target="_blank" rel="noopener" class="hover:text-white transition">Instagram</a>
                    <a href="https://x.com" target="_blank" rel="noopener" class="hover:text-white transition">X / Twitter</a>
                    <a href="https://tiktok.com" target="_blank" rel="noopener" class="hover:text-white transition">TikTok</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
