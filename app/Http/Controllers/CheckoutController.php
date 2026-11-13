<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\StoreSetting;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly PaymentService $paymentService
    ) {}

    public function index(): View|RedirectResponse
    {
        $summary = $this->cartService->getSummary();
        if ($summary['items']->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your shopping bag is empty. Select a piece to proceed to checkout.');
        }

        $user = Auth::user();
        $addresses = $user ? $user->addresses : collect();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        $codEnabled = (bool) StoreSetting::getValue('cod_enabled', '1');

        return view('checkout.index', compact('summary', 'user', 'addresses', 'defaultAddress', 'codEnabled'));
    }

    public function process(Request $request): RedirectResponse
    {
        $summary = $this->cartService->getSummary($request->input('shipping_method', 'standard'));

        if ($summary['items']->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your shopping bag is empty.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_email' => ['required', 'email', 'max:180'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_state' => ['required', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:30'],
            'shipping_country' => ['nullable', 'string', 'max:100'],
            'shipping_method' => ['required', 'in:standard,express,lagos_same_day'],
            'payment_method' => ['required', 'in:paystack,flutterwave,cod'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'save_address' => ['nullable', 'boolean'],
        ]);

        [$order, $payment] = DB::transaction(function () use ($validated, $summary, $request) {
            $orderNumber = 'SXO-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
            $trackingNumber = 'TRK-SXO-' . strtoupper(Str::random(8));

            $order = Order::create([
                'order_number' => $orderNumber,
                'tracking_number' => $trackingNumber,
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                'shipping_country' => $validated['shipping_country'] ?? 'Nigeria',
                'shipping_method' => $validated['shipping_method'],
                'subtotal' => $summary['subtotal'],
                'discount_amount' => $summary['discount'],
                'coupon_code' => $summary['coupon']?->code,
                'shipping_fee' => $summary['shipping_fee'],
                'total_amount' => $summary['total'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'order_status' => $validated['payment_method'] === 'cod' ? 'confirmed' : 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($summary['items'] as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product?->name ?? 'SAVAGEXO Piece',
                    'product_sku' => $cartItem->product?->sku,
                    'product_image' => $cartItem->product?->primary_image_url,
                    'size' => $cartItem->size,
                    'color' => $cartItem->color,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'line_total' => $cartItem->line_total,
                ]);

                if ($cartItem->product) {
                    $cartItem->product->decrement('stock_quantity', min($cartItem->quantity, $cartItem->product->stock_quantity));
                    $cartItem->product->increment('sales_count', $cartItem->quantity);
                }

                if ($cartItem->variant) {
                    $cartItem->variant->decrement('stock_quantity', min($cartItem->quantity, $cartItem->variant->stock_quantity));
                }
            }

            if ($summary['coupon']) {
                $summary['coupon']->increment('used_count');
            }

            if (Auth::check() && $request->boolean('save_address')) {
                Auth::user()->addresses()->create([
                    'label' => 'Saved at Checkout',
                    'recipient_name' => $validated['customer_name'],
                    'phone' => $validated['customer_phone'],
                    'street_address' => $validated['shipping_address'],
                    'city' => $validated['shipping_city'],
                    'state' => $validated['shipping_state'],
                    'postal_code' => $validated['shipping_postal_code'] ?? null,
                    'country' => $validated['shipping_country'] ?? 'Nigeria',
                    'is_default' => Auth::user()->addresses()->count() === 0,
                ]);
            }

            $payment = $this->paymentService->initializePayment($order, $validated['payment_method']);

            $this->cartService->clearCart();

            return [$order, $payment];
        });

        if ($order->payment_method === 'cod') {
            return redirect()->route('checkout.confirmation', $order->order_number)
                ->with('success', 'Your order has been placed with Cash on Delivery. Our concierge team will contact you shortly.');
        }

        return redirect()->route('payment.gateway', $payment->transaction_reference);
    }

    public function paymentGateway(string $reference): View
    {
        $payment = Payment::with('order.items')->where('transaction_reference', $reference)->firstOrFail();
        $signature = $this->paymentService->generateSandboxSignature($payment);

        return view('checkout.gateway', compact('payment', 'signature'));
    }

    public function verifyPayment(Request $request, string $reference): RedirectResponse
    {
        $payment = Payment::with('order')->where('transaction_reference', $reference)->firstOrFail();

        $result = $this->paymentService->verifyTransaction($payment, [
            'signature' => $request->input('signature', ''),
            'outcome' => $request->input('outcome', 'success'),
            'channel' => $request->input('channel', 'card'),
            'gateway_reference' => $request->input('gateway_reference'),
        ]);

        if ($result['verified']) {
            return redirect()->route('checkout.confirmation', $payment->order->order_number)
                ->with('success', 'Payment verified via ' . strtoupper($payment->gateway) . '. Your order is now confirmed.');
        }

        return redirect()->route('checkout.confirmation', $payment->order->order_number)
            ->with('error', 'Payment verification was unsuccessful. You may retry payment from your order summary.');
    }

    public function confirmation(string $orderNumber): View
    {
        $order = Order::with(['items.product', 'latestPayment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('checkout.confirmation', compact('order'));
    }
}
