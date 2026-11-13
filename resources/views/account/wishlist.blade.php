<x-layouts.app title="Saved Wishlist">
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            @include('account.partials.sidebar')

            <div class="lg:col-span-9 space-y-6">
                <div class="border-b border-[#E2DDD3] pb-4 flex items-center justify-between">
                    <div>
                        <p class="font-mono-tech text-xs uppercase tracking-[0.22em] text-[#B85435]">PERSONAL ARCHIVE</p>
                        <h1 class="font-editorial text-3xl sm:text-4xl font-bold mt-1">Saved Wishlist ({{ $wishlists->count() }})</h1>
                    </div>
                </div>

                @if($wishlists->isEmpty())
                    <div class="bg-[#EFECE6] border border-[#E2DDD3] p-12 text-center space-y-4">
                        <p class="text-xs text-neutral-600">Your wishlist is currently empty. Save your favorite pieces while browsing the collection.</p>
                        <a href="{{ route('shop.index') }}" class="inline-block btn-savage-primary px-6 py-3 text-xs font-bold uppercase tracking-widest">Explore Collections</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($wishlists as $wItem)
                            @if($wItem->product)
                                <x-product-card :product="$wItem->product" />
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>
