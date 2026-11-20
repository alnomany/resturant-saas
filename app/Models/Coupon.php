<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Coupon extends Model
{
    protected $fillable = [
           'restaurant_id',
        'branch_id',
        'code',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'used',
        'starts_at',
        'expires_at',
        'is_active',
        'ad_title',
        'ad_description',
        'ad_image',
        'button_text'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
      public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isExhausted(): bool
    {
        return $this->usage_limit && $this->used >= $this->usage_limit;
    }

}
