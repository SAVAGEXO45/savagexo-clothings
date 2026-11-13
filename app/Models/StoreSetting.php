<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class StoreSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $settings = Cache::remember('savagexo_store_settings', 300, function () {
            try {
                return static::query()->pluck('value', 'key')->toArray();
            } catch (\Throwable) {
                return [];
            }
        });

        return $settings[$key] ?? $default;
    }

    public static function setValue(string $key, mixed $value, string $group = 'general', string $type = 'text'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'group' => $group, 'type' => $type]
        );
        Cache::forget('savagexo_store_settings');
    }
}
