<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'payment_token',
        'recipient_name',
        'phone',
        'shipping_address',
    ];

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'در انتظار پرداخت',
            'paid' => 'پرداخت‌شده',
            'processing' => 'در حال آماده‌سازی',
            'shipped' => 'ارسال‌شده',
            'delivered' => 'تحویل‌شده',
            'cancelled' => 'لغوشده',
            default => $this->status,
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
