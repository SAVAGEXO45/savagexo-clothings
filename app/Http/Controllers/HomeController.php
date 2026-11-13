<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroBanners = Banner::where('is_active', true)
            ->where('position', 'hero')
            ->orderBy('sort_order')
            ->get();

        $sneakerHero = Banner::where('is_active', true)
            ->where('position', 'sneaker_hero')
            ->first();

        $promoBanners = Banner::where('is_active', true)
            ->whereIn('position', ['promo_primary', 'promo_secondary'])
            ->orderBy('sort_order')
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('activeProducts')
            ->orderBy('sort_order')
            ->get();

        $featuredCollections = Category::where('is_active', true)
            ->where('is_featured', true)
            ->withCount('activeProducts')
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $sneakerBrands = Brand::where('is_active', true)
            ->where('type', 'sneaker')
            ->withCount('activeProducts')
            ->orderBy('sort_order')
            ->get();

        $newSneakerArrivals = Product::with(['category', 'brand', 'variants', 'reviews'])
            ->where('is_active', true)
            ->where('product_type', 'sneaker')
            ->where('is_new_arrival', true)
            ->latest('id')
            ->take(4)
            ->get();

        $nikeCollection = Product::with(['category', 'brand', 'variants', 'reviews'])
            ->where('is_active', true)
            ->whereIn('brand_name', ['Nike', 'Jordan'])
            ->take(4)
            ->get();

        $adidasCollection = Product::with(['category', 'brand', 'variants', 'reviews'])
            ->where('is_active', true)
            ->where('brand_name', 'Adidas')
            ->take(4)
            ->get();

        $pumaCollection = Product::with(['category', 'brand', 'variants', 'reviews'])
            ->where('is_active', true)
            ->whereIn('brand_name', ['Puma', 'New Balance'])
            ->take(4)
            ->get();

        $newArrivals = Product::with(['category', 'brand', 'variants', 'reviews'])
            ->where('is_active', true)
            ->where('product_type', '!=', 'sneaker')
            ->where('is_new_arrival', true)
            ->latest()
            ->take(4)
            ->get();

        $bestSellers = Product::with(['category', 'brand', 'variants', 'reviews'])
            ->where('is_active', true)
            ->where('product_type', '!=', 'sneaker')
            ->where('is_best_seller', true)
            ->orderByDesc('sales_count')
            ->take(4)
            ->get();

        $trendingStreetwear = Product::with(['category', 'brand', 'variants', 'reviews'])
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereHas('category', fn ($cq) => $cq->where('slug', 'streetwear'))
                    ->orWhere('sneaker_style', 'Streetwear');
            })
            ->take(4)
            ->get();

        $mensCollection = Product::with(['category', 'brand', 'variants', 'reviews'])
            ->where('is_active', true)
            ->where('product_type', '!=', 'sneaker')
            ->whereIn('gender', ['men', 'unisex'])
            ->take(4)
            ->get();

        $womensCollection = Product::with(['category', 'brand', 'variants', 'reviews'])
            ->where('is_active', true)
            ->where('product_type', '!=', 'sneaker')
            ->whereIn('gender', ['women', 'unisex'])
            ->latest('id')
            ->take(4)
            ->get();

        $testimonials = Review::with(['user', 'product'])
            ->where('is_approved', true)
            ->where('rating', '>=', 4)
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact(
            'heroBanners',
            'sneakerHero',
            'promoBanners',
            'categories',
            'featuredCollections',
            'sneakerBrands',
            'newSneakerArrivals',
            'nikeCollection',
            'adidasCollection',
            'pumaCollection',
            'newArrivals',
            'bestSellers',
            'trendingStreetwear',
            'mensCollection',
            'womensCollection',
            'testimonials'
        ));
    }

    public function subscribeNewsletter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => strtolower($validated['email'])],
            ['is_active' => true]
        );

        return back()->with('success', 'Welcome to the SAVAGEXO Private List. Your 15% privilege code is SAVAGE15. Contact us anytime at SAVAGEXO@GMAIL.COM.');
    }

    public function trackOrder(Request $request): View
    {
        $order = null;
        $query = trim((string) $request->query('code', ''));

        if ($query !== '') {
            $order = Order::with(['items.product', 'latestPayment'])
                ->where('order_number', $query)
                ->orWhere('tracking_number', $query)
                ->first();
        }

        return view('orders.track', compact('order', 'query'));
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Thank you for contacting SAVAGEXO CLOTHINGS. Our concierge team (SAVAGEXO@GMAIL.COM) will respond shortly.');
    }

    public function about(): View
    {
        return view('pages.about');
    }
}
