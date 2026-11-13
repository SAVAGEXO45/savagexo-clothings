<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'eyebrow',
        'description',
        'image_path',
        'cta_text',
        'cta_link',
        'secondary_cta_text',
        'secondary_cta_link',
        'position',
        'badge_text',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        return str_starts_with($this->image_path, 'http') || str_starts_with($this->image_path, '/')
            ? $this->image_path
            : '/' . ltrim($this->image_path, '/');
    }
}
