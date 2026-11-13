<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\StoreSetting;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth-attempts', function (Request $request) {
            return Limit::perMinute(15)->by($request->ip());
        });

        View::composer('*', function ($view) {
            try {
                $cartService = app(CartService::class);
                $cart = $cartService->getCart(false);
                $cartCount = $cart ? (int) $cart->items->sum('quantity') : 0;

                $wishlistIds = [];
                if (Auth::check()) {
                    $wishlistIds = Wishlist::where('user_id', Auth::id())
                        ->pluck('product_id')
                        ->toArray();
                }

                $navCategories = Category::where('is_active', true)
                    ->orderBy('sort_order')
                    ->take(8)
                    ->get();

                $announcementText = StoreSetting::getValue(
                    'announcement_text',
                    'LAGOS • LONDON • MILAN — COMPLIMENTARY EXPRESS DELIVERY ON ORDERS OVER ₦250,000 | CONTACT: SAVAGEXO@GMAIL.COM'
                );

                $contactEmail = StoreSetting::getValue('contact_email', 'SAVAGEXO@GMAIL.COM');

                $view->with([
                    'globalCartCount' => $cartCount,
                    'globalWishlistCount' => count($wishlistIds),
                    'globalWishlistIds' => $wishlistIds,
                    'globalCategories' => $navCategories,
                    'globalAnnouncementText' => $announcementText,
                    'globalContactEmail' => $contactEmail,
                ]);
            } catch (\Throwable) {
                $view->with([
                    'globalCartCount' => 0,
                    'globalWishlistCount' => 0,
                    'globalWishlistIds' => [],
                    'globalCategories' => collect(),
                    'globalAnnouncementText' => 'SAVAGEXO CLOTHINGS — LUXURY CONTEMPORARY FASHION & SNEAKER DESTINATION',
                    'globalContactEmail' => 'SAVAGEXO@GMAIL.COM',
                ]);
            }
        });
    }
}
