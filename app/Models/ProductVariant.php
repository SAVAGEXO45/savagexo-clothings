<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'color_hex',
        'sku_variant',
        'stock_quantity',
        'price_adjustment',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'stock_quantity' => 'integer',
            'price_adjustment' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
