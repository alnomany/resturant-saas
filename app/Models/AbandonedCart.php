<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbandonedCart extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'store_id',
        'session_id',
        'phone',
        'name',
        'cart_data',
        'total_amount',
        'status',
        'last_active_at',
        'reminder_sent_at',
    ];

    protected $casts = [
        'cart_data' => 'array', // يحول الـ JSON تلقائياً إلى Array عند الاستدعاء
        'last_active_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    /**
     * علاقة السلة بالمطعم/المتجر
     */
    // app/Models/AbandonedCart.php
public function getProductsSummaryAttribute(): string
{
    return collect($this->cart_data)
        ->map(fn ($item) => ($item['name'] ?? 'منتج') . ' ×' . ($item['quantity'] ?? 1))
        ->implode('، ');
}
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'store_id');
    }
      public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }
}
