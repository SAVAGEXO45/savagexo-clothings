<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart(bool $create = true): ?Cart
    {
        $user = Auth::user();
        $sessionId = Session::getId();

        if ($user) {
            $cart = Cart::with(['items.product.category', 'items.variant'])
                ->where('user_id', $user->id)
                ->first();

            // Merge guest session cart if present
            $sessionCart = Cart::with('items')
                ->whereNull('user_id')
                ->where('session_id', $sessionId)
                ->first();

            if ($sessionCart) {
                if (! $cart) {
                    $sessionCart->update(['user_id' => $user->id]);
                    $cart = $sessionCart->fresh(['items.product.category', 'items.variant']);
                } else {
                    foreach ($sessionCart->items as $sItem) {
                        $existing = $cart->items()
                            ->where('product_id', $sItem->product_id)
                            ->where('size', $sItem->size)
                            ->where('color', $sItem->color)
                            ->first();

                        if ($existing) {
                            $existing->increment('quantity', $sItem->quantity);
                        } else {
                            $sItem->update(['cart_id' => $cart->id]);
                        }
                    }
                    if (! $cart->coupon_code && $sessionCart->coupon_code) {
                        $cart->update(['coupon_code' => $sessionCart->coupon_code]);
                    }
                    $sessionCart->delete();
                    $cart->load(['items.product.category', 'items.variant']);
                }
            }

            if (! $cart && $create) {
                $cart = Cart::create([
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                ]);
                $cart->load(['items.product.category', 'items.variant']);
            }

            return $cart;
        }

        $cart = Cart::with(['items.product.category', 'items.variant'])
            ->whereNull('user_id')
            ->where('session_id', $sessionId)
            ->first();

        if (! $cart && $create) {
            $cart = Cart::create([
                'user_id' => null,
                'session_id' => $sessionId,
            ]);
            $cart->load(['items.product.category', 'items.variant']);
        }

        return $cart;
    }

    public function addItem(
        Product $product,
        int $quantity = 1,
        ?string $size = null,
        ?string $color = null,
        ?int $variantId = null
    ): CartItem {
        $cart = $this->getCart(true);

        $variant = null;
        if ($variantId) {
            $variant = ProductVariant::where('product_id', $product->id)->find($variantId);
        }

        if (! $variant && ($size || $color)) {
            $query = ProductVariant::where('product_id', $product->id);
            if ($size) {
                $query->where('size', $size);
            }
            if ($color) {
                $query->where('color', $color);
            }
            $variant = $query->first();
        }

        if (! $variant) {
            $variant = $product->variants()->first();
        }

        $resolvedSize = $size ?: ($variant?->size ?? 'M');
        $resolvedColor = $color ?: ($variant?->color ?? 'Obsidian Black');
        $resolvedHex = $variant?->color_hex ?? '#111111';
        $unitPrice = $product->effective_price + (float) ($variant?->price_adjustment ?? 0);

        $maxStock = $variant ? $variant->stock_quantity : $product->stock_quantity;
        $quantity = max(1, min($quantity, max(1, $maxStock)));

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('size', $resolvedSize)
            ->where('color', $resolvedColor)
            ->first();

        if ($existingItem) {
            $newQty = min($existingItem->quantity + $quantity, max(1, $maxStock));
            $existingItem->update([
                'quantity' => $newQty,
                'unit_price' => $unitPrice,
            ]);
            return $existingItem->fresh(['product', 'variant']);
        }

        return CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
            'size' => $resolvedSize,
            'color' => $resolvedColor,
            'color_hex' => $resolvedHex,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
        ]);
    }

    public function getSummary(?string $shippingMethod = 'standard'): array
    {
        $cart = $this->getCart(false);

        if (! $cart || $cart->items->isEmpty()) {
            return [
                'cart' => $cart,
                'items' => collect(),
                'item_count' => 0,
                'subtotal' => 0.0,
                'discount' => 0.0,
                'coupon' => null,
                'shipping_fee' => 0.0,
                'free_shipping_threshold' => (float) StoreSetting::getValue('free_shipping_threshold', 250000),
                'total' => 0.0,
            ];
        }

        $subtotal = (float) $cart->items->sum(fn (CartItem $item) => (float) $item->unit_price * $item->quantity);
        $itemCount = (int) $cart->items->sum('quantity');

        $discount = 0.0;
        $coupon = null;
        if ($cart->coupon_code) {
            $coupon = Coupon::where('code', strtoupper($cart->coupon_code))->first();
            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                $cart->update(['coupon_code' => null]);
                $coupon = null;
            }
        }

        $freeShippingThreshold = (float) StoreSetting::getValue('free_shipping_threshold', 250000);
        $standardFee = (float) StoreSetting::getValue('shipping_fee_standard', 4500);
        $expressFee = (float) StoreSetting::getValue('shipping_fee_express', 9500);
        $sameDayFee = (float) StoreSetting::getValue('shipping_fee_lagos_same_day', 15000);

        $shippingFee = match ($shippingMethod) {
            'express' => $expressFee,
            'lagos_same_day' => $sameDayFee,
            default => ($subtotal >= $freeShippingThreshold ? 0.0 : $standardFee),
        };

        $total = max(0, ($subtotal - $discount) + $shippingFee);

        return [
            'cart' => $cart,
            'items' => $cart->items,
            'item_count' => $itemCount,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon' => $coupon,
            'shipping_fee' => $shippingFee,
            'shipping_rates' => [
                'standard' => $subtotal >= $freeShippingThreshold ? 0.0 : $standardFee,
                'express' => $expressFee,
                'lagos_same_day' => $sameDayFee,
            ],
            'free_shipping_threshold' => $freeShippingThreshold,
            'total' => $total,
        ];
    }

    public function clearCart(): void
    {
        $cart = $this->getCart(false);
        if ($cart) {
            $cart->items()->delete();
            $cart->update(['coupon_code' => null]);
        }
    }
}
