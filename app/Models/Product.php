<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'brand_name',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'discount_price',
        'stock_quantity',
        'gender',
        'sneaker_style',
        'product_type',
        'material',
        'care_instructions',
        'featured_image',
        'is_featured',
        'is_new_arrival',
        'is_best_seller',
        'is_trending',
        'is_active',
        'views_count',
        'sales_count',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_trending' => 'boolean',
            'is_active' => 'boolean',
            'views_count' => 'integer',
            'sales_count' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function allVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->latest();
    }

    public function allReviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return (float) $this->discount_price;
        }
        return (float) $this->price;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_price !== null
            && (float) $this->discount_price > 0
            && (float) $this->discount_price < (float) $this->price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (! $this->has_discount || (float) $this->price <= 0) {
            return 0;
        }

        return (int) round(((($this->price - $this->discount_price) / $this->price) * 100));
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₦' . number_format((float) $this->price, 0);
    }

    public function getFormattedEffectivePriceAttribute(): string
    {
        return '₦' . number_format($this->effective_price, 0);
    }

    public function getAverageRatingAttribute(): float
    {
        if ($this->relationLoaded('reviews')) {
            return round((float) ($this->reviews->avg('rating') ?? 4.9), 1);
        }
        return round((float) ($this->reviews()->avg('rating') ?? 4.9), 1);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            return str_starts_with($this->featured_image, 'http') || str_starts_with($this->featured_image, '/')
                ? $this->featured_image
                : '/' . ltrim($this->featured_image, '/');
        }

        return '/images/products/agbada-noir.jpg';
    }

    public function getAvailableSizesAttribute(): array
    {
        if ($this->relationLoaded('variants')) {
            return $this->variants->pluck('size')->unique()->values()->all();
        }
        return $this->variants()->pluck('size')->unique()->values()->all();
    }

    public function getAvailableColorsAttribute(): array
    {
        $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->get();
        return $variants->unique('color')->map(fn ($v) => [
            'name' => $v->color,
            'hex' => $v->color_hex,
        ])->values()->all();
    }

    public function getDisplayBrandAttribute(): string
    {
        if ($this->brand_name) {
            return $this->brand_name;
        }
        if ($this->relationLoaded('brand') && $this->brand) {
            return $this->brand->name;
        }
        return 'SAVAGEXO';
    }
}
