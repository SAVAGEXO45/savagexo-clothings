<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products/search', function (Request $request) {
    $q = trim((string) $request->query('q', ''));
    if (strlen($q) < 2) {
        return response()->json(['data' => []]);
    }

    $products = Product::with(['category', 'brand'])
        ->where('is_active', true)
        ->where(function ($query) use ($q) {
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('brand_name', 'like', "%{$q}%")
                ->orWhere('sneaker_style', 'like', "%{$q}%")
                ->orWhere('sku', 'like', "%{$q}%");
        })
        ->take(8)
        ->get()
        ->map(fn (Product $p) => [
            'id' => $p->id,
            'name' => $p->name,
            'brand' => $p->display_brand,
            'slug' => $p->slug,
            'category' => $p->category?->name,
            'price_formatted' => $p->formatted_effective_price,
            'image' => $p->primary_image_url,
            'url' => route('shop.show', $p->slug),
        ]);

    return response()->json(['data' => $products]);
});
