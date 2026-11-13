<x-layouts.app title="Address Book">
    <section class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            @include('account.partials.sidebar')

            <div class="lg:col-span-9 space-y-10">
                <div class="border-b border-[#E2DDD3] pb-4">
                    <p class="font-mono-tech text-xs uppercase tracking-[0.22em] text-[#B85435]">COURIER DESTINATIONS</p>
                    <h1 class="font-editorial text-3xl sm:text-4xl font-bold mt-1">Address Book</h1>
                </div>

                <!-- Saved Addresses Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($addresses as $address)
                        <div class="bg-white border border-[#0E0E0E] p-6 flex flex-col justify-between space-y-4">
                            <div class="space-y-1 text-xs">
                                <div class="flex items-center justify-between pb-2 border-b border-[#E2DDD3]">
                                    <span class="font-bold uppercase tracking-widest">{{ $address->label }}</span>
                                    @if($address->is_default)
                                        <span class="font-mono-tech text-[10px] bg-[#0E0E0E] text-[#C9A96E] px-2.5 py-0.5">DEFAULT</span>
                                    @endif
                                </div>
                                <p class="font-bold pt-2">{{ $address->recipient_name }}</p>
                                <p class="text-neutral-600">{{ $address->street_address }}</p>
                                @if($address->apartment)
                                    <p class="text-neutral-600">{{ $address->apartment }}</p>
                                @endif
                                <p class="text-neutral-600">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                <p class="text-neutral-600">{{ $address->country }}</p>
                                <p class="font-mono-tech text-neutral-500 pt-1">{{ $address->phone }}</p>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-[#E2DDD3] text-xs">
                                @if(!$address->is_default)
                                    <form action="{{ route('account.addresses.default', $address) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="font-mono-tech text-[11px] uppercase tracking-wider text-[#B85435] hover:underline">
                                            Set as Default
                                        </button>
                                    </form>
                                @else
                                    <span class="font-mono-tech text-[11px] text-neutral-400">Primary Destination</span>
                                @endif

                                <form action="{{ route('account.addresses.delete', $address) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-mono-tech text-[11px] uppercase tracking-wider text-red-700 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Add New Address Form -->
                <div class="bg-[#EFECE6] border border-[#0E0E0E] p-8 space-y-6">
                    <h2 class="font-editorial text-2xl font-bold">Add New Delivery Address</h2>

                    <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-mono-tech text-[11px] uppercase mb-1">Address Label *</label>
                                <input type="text" name="label" required placeholder="e.g. Victoria Island Residence" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                            </div>
                            <div>
                                <label class="block font-mono-tech text-[11px] uppercase mb-1">Recipient Name *</label>
                                <input type="text" name="recipient_name" required value="{{ auth()->user()->name }}" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                            </div>
                            <div>
                                <label class="block font-mono-tech text-[11px] uppercase mb-1">Phone Number *</label>
                                <input type="text" name="phone" required value="{{ auth()->user()->phone }}" placeholder="+234 803 000 0000" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-mono-tech text-[11px] uppercase mb-1">Street Address *</label>
                                <input type="text" name="street_address" required placeholder="14B Adeola Odeku Street" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                            </div>
                            <div>
                                <label class="block font-mono-tech text-[11px] uppercase mb-1">Apartment / Suite</label>
                                <input type="text" name="apartment" placeholder="Penthouse 4" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="block font-mono-tech text-[11px] uppercase mb-1">City *</label>
                                <input type="text" name="city" required placeholder="Lagos" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                            </div>
                            <div>
                                <label class="block font-mono-tech text-[11px] uppercase mb-1">State *</label>
                                <input type="text" name="state" required placeholder="Lagos State" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                            </div>
                            <div>
                                <label class="block font-mono-tech text-[11px] uppercase mb-1">Postal Code</label>
                                <input type="text" name="postal_code" placeholder="106104" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                            </div>
                            <div>
                                <label class="block font-mono-tech text-[11px] uppercase mb-1">Country</label>
                                <input type="text" name="country" value="Nigeria" class="w-full bg-white border border-[#0E0E0E] px-3.5 py-2.5">
                            </div>
                        </div>

                        <label class="inline-flex items-center gap-2 cursor-pointer pt-1">
                            <input type="checkbox" name="is_default" value="1" class="accent-[#0E0E0E]">
                            <span>Set as default delivery address</span>
                        </label>

                        <div>
                            <button type="submit" class="btn-savage-primary px-8 py-3.5 text-xs font-bold uppercase tracking-[0.2em]">
                                Save Address
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
