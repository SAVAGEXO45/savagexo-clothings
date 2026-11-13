<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'brand', 'images', 'variants', 'reviews'])
            ->where('is_active', true);

        $this->applyCatalogFilters($query, $request);

        $sort = $request->query('sort', 'newest');
        $this->applySorting($query, $sort);

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)
            ->withCount('activeProducts')
            ->orderBy('sort_order')
            ->get();

        $brands = Brand::where('is_active', true)
            ->withCount('activeProducts')
            ->orderBy('sort_order')
            ->get();

        $activeCategory = $request->query('category')
            ? Category::where('slug', $request->query('category'))->first()
            : null;

        $activeBrand = $request->query('brand')
            ? Brand::where('slug', $request->query('brand'))->orWhere('name', $request->query('brand'))->first()
            : null;

        $allSizes = ProductVariant::where('is_active', true)->select('size')->distinct()->pluck('size');
        $clothingSizes = $allSizes->filter(fn ($s) => ! is_numeric($s))->values();
        $shoeSizes = $allSizes->filter(fn ($s) => is_numeric($s))->sort()->values();

        $availableColors = ProductVariant::where('is_active', true)
            ->select('color', 'color_hex')
            ->distinct()
            ->get()
            ->unique('color')
            ->values();

        $sneakerStyles = [
            'Running', 'Basketball', 'Lifestyle', 'Casual', 'Retro',
            'High-top', 'Low-top', 'Training', 'Luxury', 'Streetwear',
        ];

        return view('shop.index', compact(
            'products',
            'categories',
            'brands',
            'activeCategory',
            'activeBrand',
            'clothingSizes',
            'shoeSizes',
            'availableColors',
            'sneakerStyles',
            'sort'
        ));
    }

    public function sneakers(Request $request): View
    {
        $sneakerCategory = Category::where('slug', 'sneakers')->first();

        $query = Product::with(['category', 'brand', 'images', 'variants', 'reviews'])
            ->where('is_active', true)
            ->where(function ($q) use ($sneakerCategory) {
                $q->where('product_type', 'sneaker');
                if ($sneakerCategory) {
                    $q->orWhere('category_id', $sneakerCategory->id);
                }
            });

        $this->applyCatalogFilters($query, $request);

        $sort = $request->query('sort', 'newest');
        $this->applySorting($query, $sort);

        $products = $query->paginate(12)->withQueryString();

        $brands = Brand::where('is_active', true)
            ->where('type', 'sneaker')
            ->withCount('activeProducts')
            ->orderBy('sort_order')
            ->get();

        $activeBrand = $request->query('brand')
            ? Brand::where('slug', $request->query('brand'))->orWhere('name', $request->query('brand'))->first()
            : null;

        $shoeSizes = ProductVariant::where('is_active', true)
            ->select('size')
            ->distinct()
            ->pluck('size')
            ->filter(fn ($s) => is_numeric($s))
            ->sort()
            ->values();

        $availableColors = ProductVariant::where('is_active', true)
            ->whereHas('product', fn ($q) => $q->where('product_type', 'sneaker'))
            ->select('color', 'color_hex')
            ->distinct()
            ->get()
            ->unique('color')
            ->values();

        $sneakerStyles = [
            'Running', 'Basketball', 'Lifestyle', 'Casual', 'Retro',
            'High-top', 'Low-top', 'Training', 'Luxury', 'Streetwear',
        ];

        return view('shop.sneakers', compact(
            'products',
            'brands',
            'activeBrand',
            'shoeSizes',
            'availableColors',
            'sneakerStyles',
            'sort'
        ));
    }

    private function applyCatalogFilters(Builder $query, Request $request): void
    {
        // Search keyword
        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%")
                    ->orWhere('sneaker_style', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($categorySlug = $request->query('category')) {
            $cat = Category::where('slug', $categorySlug)->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        // Brand filter (by slug or name)
        if ($brandParam = trim((string) $request->query('brand', ''))) {
            $brandModel = Brand::where('slug', $brandParam)->orWhere('name', $brandParam)->first();
            if ($brandModel) {
                $query->where(function ($q) use ($brandModel) {
                    $q->where('brand_id', $brandModel->id)
                        ->orWhere('brand_name', $brandModel->name);
                });
            } else {
                $query->where('brand_name', 'like', "%{$brandParam}%");
            }
        }

        // Sneaker style filter
        if ($style = trim((string) $request->query('style', ''))) {
            $query->where('sneaker_style', $style);
        }

        // Gender filter
        if ($gender = $request->query('gender')) {
            if (in_array($gender, ['men', 'women', 'unisex'], true)) {
                $query->whereIn('gender', [$gender, 'unisex']);
            }
        }

        // Collection / New Arrivals / Best Sellers flags
        if ($collection = $request->query('collection')) {
            match ($collection) {
                'new-arrivals' => $query->where('is_new_arrival', true),
                'best-sellers' => $query->where('is_best_seller', true),
                'trending' => $query->where('is_trending', true),
                'sale' => $query->whereNotNull('discount_price')->whereColumn('discount_price', '<', 'price'),
                default => null,
            };
        }

        if ($request->boolean('new_arrivals')) {
            $query->where('is_new_arrival', true);
        }

        if ($request->boolean('best_sellers')) {
            $query->where('is_best_seller', true);
        }

        // Price range
        if ($minPrice = $request->query('min_price')) {
            $query->whereRaw('COALESCE(discount_price, price) >= ?', [(float) $minPrice]);
        }

        if ($maxPrice = $request->query('max_price')) {
            $query->whereRaw('COALESCE(discount_price, price) <= ?', [(float) $maxPrice]);
        }

        // Clothing size or Shoe size or generic size
        $sizeFilter = $request->query('size') ?: ($request->query('clothing_size') ?: $request->query('shoe_size'));
        if ($sizeFilter) {
            $query->whereHas('variants', fn ($q) => $q->where('size', $sizeFilter));
        }

        // Color filter
        if ($color = $request->query('color')) {
            $query->whereHas('variants', fn ($q) => $q->where('color', $color));
        }

        // Availability
        if ($availability = $request->query('availability')) {
            if ($availability === 'in_stock') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($availability === 'on_sale') {
                $query->whereNotNull('discount_price')->whereColumn('discount_price', '<', 'price');
            }
        }
    }

    private function applySorting(Builder $query, string $sort): void
    {
        match ($sort) {
            'popularity' => $query->orderByDesc('views_count')->orderByDesc('sales_count'),
            'best_selling' => $query->orderByDesc('sales_count')->orderByDesc('views_count'),
            'price_asc' => $query->orderByRaw('COALESCE(discount_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(discount_price, price) DESC'),
            default => $query->latest('id'),
        };
    }

    public function show(string $slug): View
    {
        $product = Product::with([
            'category',
            'brand',
            'images',
            'variants',
            'reviews.user',
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $product->increment('views_count');

        $relatedProducts = Product::with(['category', 'brand', 'images', 'variants', 'reviews'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                    ->orWhere('brand_name', $product->brand_name);
            })
            ->take(4)
            ->get();

        $canReview = Auth::check();
        $hasVerifiedOrder = false;
        if (Auth::check()) {
            $hasVerifiedOrder = Order::where('user_id', Auth::id())
                ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
                ->exists();
        }

        return view('shop.show', compact('product', 'relatedProducts', 'canReview', 'hasVerifiedOrder'));
    }

    public function storeReview(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:120'],
            'comment' => ['required', 'string', 'min:10', 'max:1500'],
        ]);

        $verifiedOrder = Order::where('user_id', Auth::id())
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
            ->first();

        Review::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => Auth::id(),
            ],
            [
                'order_id' => $verifiedOrder?->id,
                'rating' => $validated['rating'],
                'title' => $validated['title'],
                'comment' => $validated['comment'],
                'is_verified_purchase' => $verifiedOrder !== null,
                'is_approved' => true,
            ]
        );

        return back()->with('success', 'Thank you. Your review has been published.');
    }
}
