<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->with(['items', 'latestPayment'])->take(5)->get();
        $wishlistItems = $user->wishlists()->with('product.category')->take(4)->get();
        $addresses = $user->addresses;

        $totalSpent = (float) $user->orders()->where('payment_status', 'paid')->sum('total_amount');

        return view('account.dashboard', compact('user', 'recentOrders', 'wishlistItems', 'addresses', 'totalSpent'));
    }

    public function orders(): View
    {
        $orders = Auth::user()->orders()->with(['items', 'latestPayment'])->paginate(10);
        return view('account.orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber): View
    {
        $order = Order::with(['items.product', 'payments'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('account.order-detail', compact('order'));
    }

    public function wishlist(): View
    {
        $wishlists = Auth::user()->wishlists()->with(['product.category', 'product.variants'])->latest()->get();
        return view('account.wishlist', compact('wishlists'));
    }

    public function toggleWishlist(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $inWishlist = false;
            $message = "{$product->name} removed from your wishlist.";
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $inWishlist = true;
            $message = "{$product->name} saved to your wishlist.";
        }

        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'in_wishlist' => $inWishlist,
                'wishlist_count' => $wishlistCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function addresses(): View
    {
        $addresses = Auth::user()->addresses;
        return view('account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'street_address' => ['required', 'string', 'max:255'],
            'apartment' => ['nullable', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'country' => ['nullable', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $user = Auth::user();
        $makeDefault = $request->boolean('is_default') || $user->addresses()->count() === 0;

        if ($makeDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'label' => $validated['label'],
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'street_address' => $validated['street_address'],
            'apartment' => $validated['apartment'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'] ?? null,
            'country' => $validated['country'] ?? 'Nigeria',
            'is_default' => $makeDefault,
        ]);

        return back()->with('success', 'Delivery address saved.');
    }

    public function setDefaultAddress(Address $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Default delivery address updated.');
    }

    public function deleteAddress(Address $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();
        return back()->with('success', 'Address removed.');
    }

    public function profile(): View
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'] ?? null,
        ]);

        return back()->with('success', 'Profile details updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Your password has been updated.');
    }
}
