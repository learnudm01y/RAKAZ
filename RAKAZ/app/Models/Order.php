<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'subtotal',
        'shipping_cost',
        'tax',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'payment_reference',
        'paid_at',
        'status',
        'notes',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return $prefix . '-' . $date . '-' . $random;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => app()->getLocale() == 'ar' ? 'قيد الانتظار' : 'Pending',
            'confirmed' => app()->getLocale() == 'ar' ? 'قيد التحضير' : 'Confirmed',
            'processing' => app()->getLocale() == 'ar' ? 'قيد المعالجة' : 'Processing',
            'shipped' => app()->getLocale() == 'ar' ? 'تم الشحن' : 'Shipped',
            'delivered' => app()->getLocale() == 'ar' ? 'تم التوصيل' : 'Delivered',
            'cancelled' => app()->getLocale() == 'ar' ? 'ملغي' : 'Cancelled',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => app()->getLocale() == 'ar' ? 'قيد الانتظار' : 'Pending',
            'paid' => app()->getLocale() == 'ar' ? 'مدفوع' : 'Paid',
            'failed' => app()->getLocale() == 'ar' ? 'فشل' : 'Failed',
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }
}
