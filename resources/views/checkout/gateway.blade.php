<x-layouts.app title="Payment Authorization">
    <section class="max-w-xl mx-auto px-4 py-16">
        <div class="bg-white border-2 border-[#0E0E0E] shadow-2xl overflow-hidden">
            <!-- Gateway Top Header -->
            <div class="bg-[#0E0E0E] text-white p-6 flex items-center justify-between">
                <div>
                    <span class="font-mono-tech text-[10px] uppercase tracking-[0.25em] text-[#C9A96E]">
                        {{ strtoupper($payment->gateway) }} SECURE SANDBOX CHECKOUT
                    </span>
                    <h1 class="font-editorial text-2xl font-bold mt-1">Authorize Payment</h1>
                </div>
                <div class="text-right font-mono-tech">
                    <p class="text-[10px] text-neutral-400">AMOUNT (NGN)</p>
                    <p class="text-xl font-bold text-[#C9A96E]">₦{{ number_format((float) $payment->amount, 0) }}</p>
                </div>
            </div>

            <!-- Order & Reference Info -->
            <div class="p-6 sm:p-8 space-y-6">
                <div class="bg-[#F7F5F0] border border-[#E2DDD3] p-4 space-y-2 text-xs font-mono-tech">
                    <div class="flex justify-between">
                        <span class="text-neutral-500">ORDER NUMBER:</span>
                        <span class="font-bold">{{ $payment->order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">TX REFERENCE:</span>
                        <span class="font-bold">{{ $payment->transaction_reference }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">CUSTOMER:</span>
                        <span>{{ $payment->order->customer_email }}</span>
                    </div>
                </div>

                <!-- Payment Channel Selection (No raw card numbers stored) -->
                <form action="{{ route('payment.verify', $payment->transaction_reference) }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="signature" value="{{ $signature }}">

                    <div>
                        <label class="block text-[11px] font-mono-tech uppercase tracking-wider text-neutral-600 mb-2">
                            Select Sandbox Payment Channel
                        </label>
                        <div class="grid grid-cols-3 gap-2.5 text-xs font-mono-tech">
                            <label class="border border-[#0E0E0E] p-3 text-center cursor-pointer bg-[#EFECE6]">
                                <input type="radio" name="channel" value="card" checked class="accent-[#0E0E0E] mr-1">
                                Card
                            </label>
                            <label class="border border-[#0E0E0E] p-3 text-center cursor-pointer bg-white">
                                <input type="radio" name="channel" value="bank_transfer" class="accent-[#0E0E0E] mr-1">
                                Transfer
                            </label>
                            <label class="border border-[#0E0E0E] p-3 text-center cursor-pointer bg-white">
                                <input type="radio" name="channel" value="ussd" class="accent-[#0E0E0E] mr-1">
                                USSD *737#
                            </label>
                        </div>
                    </div>

                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 leading-relaxed">
                        <strong>Security Notice:</strong> This sandbox gateway simulates {{ ucfirst($payment->gateway) }} callback verification using HMAC-SHA256 backend verification. No card numbers are collected or stored.
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <button
                            type="submit"
                            name="outcome"
                            value="success"
                            class="btn-savage-primary py-4 text-xs font-bold uppercase tracking-[0.18em]"
                        >
                            ✓ Authorize ₦{{ number_format((float) $payment->amount, 0) }}
                        </button>

                        <button
                            type="submit"
                            name="outcome"
                            value="failed"
                            class="py-4 border border-red-700 text-red-700 hover:bg-red-700 hover:text-white text-xs font-bold uppercase tracking-[0.18em] transition"
                        >
                            ✕ Simulate Decline
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layouts.app>
