<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'tracking_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'shipping_method',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'shipping_fee',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->latest();
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function getFormattedTotalAttribute(): string
    {
        return '₦' . number_format((float) $this->total_amount, 0);
    }

    public function getTimelineStepsAttribute(): array
    {
        $steps = [
            'pending' => ['label' => 'Order Placed', 'desc' => 'Order received at SAVAGEXO Atelier'],
            'confirmed' => ['label' => 'Order Confirmed', 'desc' => 'Payment verified & pieces reserved'],
            'processing' => ['label' => 'Quality Inspection & Packaging', 'desc' => 'Garments steamed & boxed in signature packaging'],
            'shipped' => ['label' => 'Dispatched with Courier', 'desc' => 'In transit via SAVAGEXO Logistics'],
            'delivered' => ['label' => 'Delivered', 'desc' => 'Signed and received at destination'],
        ];

        $statusOrder = array_keys($steps);
        $currentIndex = array_search($this->order_status, $statusOrder, true);
        if ($this->order_status === 'out_for_delivery') {
            $currentIndex = 3;
        }

        $result = [];
        foreach ($steps as $key => $info) {
            $idx = array_search($key, $statusOrder, true);
            $result[] = [
                'key' => $key,
                'label' => $info['label'],
                'desc' => $info['desc'],
                'completed' => $currentIndex !== false && $idx <= $currentIndex && $this->order_status !== 'cancelled',
                'current' => $this->order_status === $key || ($this->order_status === 'out_for_delivery' && $key === 'shipped'),
            ];
        }

        return $result;
    }
}
