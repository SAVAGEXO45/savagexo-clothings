<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    public function index(): View
    {
        $summary = $this->cartService->getSummary();
        $recommendedProducts = Product::with(['category', 'variants'])
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('cart.index', compact('summary', 'recommendedProducts'));
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:25'],
            'size' => ['nullable', 'string', 'max:30'],
            'color' => ['nullable', 'string', 'max:50'],
            'variant_id' => ['nullable', 'integer'],
        ]);

        $product = Product::where('is_active', true)->findOrFail($validated['product_id']);

        if ($product->stock_quantity <= 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This piece is currently out of stock.',
                ], 422);
            }
            return back()->with('error', 'This piece is currently out of stock.');
        }

        $item = $this->cartService->addItem(
            $product,
            (int) ($validated['quantity'] ?? 1),
            $validated['size'] ?? null,
            $validated['color'] ?? null,
            isset($validated['variant_id']) ? (int) $validated['variant_id'] : null
        );

        $summary = $this->cartService->getSummary();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$product->name} ({$item->size} / {$item->color}) added to your bag.",
                'cart_count' => $summary['item_count'],
                'subtotal_formatted' => '₦' . number_format($summary['subtotal'], 0),
            ]);
        }

        return back()->with('success', "{$product->name} ({$item->size} / {$item->color}) added to your shopping bag.");
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $cart = $this->cartService->getCart(false);
        if (! $cart || $cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $maxStock = $cartItem->variant ? $cartItem->variant->stock_quantity : ($cartItem->product?->stock_quantity ?? 20);
        $qty = min((int) $validated['quantity'], max(1, $maxStock));

        $cartItem->update(['quantity' => $qty]);
        $summary = $this->cartService->getSummary();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bag quantity updated.',
                'item_line_total' => '₦' . number_format($cartItem->fresh()->line_total, 0),
                'cart_count' => $summary['item_count'],
                'subtotal_formatted' => '₦' . number_format($summary['subtotal'], 0),
                'discount_formatted' => '₦' . number_format($summary['discount'], 0),
                'shipping_formatted' => $summary['shipping_fee'] > 0 ? '₦' . number_format($summary['shipping_fee'], 0) : 'COMPLIMENTARY',
                'total_formatted' => '₦' . number_format($summary['total'], 0),
            ]);
        }

        return back()->with('success', 'Shopping bag updated.');
    }

    public function remove(Request $request, CartItem $cartItem): RedirectResponse|JsonResponse
    {
        $cart = $this->cartService->getCart(false);
        if (! $cart || $cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $cartItem->delete();
        $summary = $this->cartService->getSummary();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from bag.',
                'cart_count' => $summary['item_count'],
            ]);
        }

        return back()->with('success', 'Item removed from your shopping bag.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:50'],
        ]);

        $code = strtoupper(trim($validated['coupon_code']));
        $coupon = Coupon::where('code', $code)->first();

        $summary = $this->cartService->getSummary();
        if (! $coupon) {
            return back()->with('error', "Privilege code '{$code}' was not recognized.");
        }

        if (! $coupon->isValidForAmount($summary['subtotal'])) {
            $minFormatted = '₦' . number_format((float) $coupon->min_order_amount, 0);
            return back()->with('error', "This privilege code requires a minimum bag subtotal of {$minFormatted} or has expired.");
        }

        $cart = $this->cartService->getCart(true);
        $cart->update(['coupon_code' => $coupon->code]);

        return back()->with('success', "Privilege code {$coupon->code} applied to your bag.");
    }

    public function removeCoupon(): RedirectResponse
    {
        $cart = $this->cartService->getCart(false);
        if ($cart) {
            $cart->update(['coupon_code' => null]);
        }

        return back()->with('success', 'Privilege code removed.');
    }
}
