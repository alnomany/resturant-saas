<?php

namespace App\Models;

use App\Traits\HasRestaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use App\Models\BaseModel;

class Customer extends BaseModel
{
    use HasFactory;
    use Notifiable;
    use HasRestaurant;

    protected $guarded = ['id'];

   public function orders(): HasMany 
{
    return $this->hasMany(Order::class, 'customer_id', 'id')->orderBy('id', 'desc');
}

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class)->orderBy('id', 'desc');
    }


    //* حساب إجمالي النقاط المتاحة للعميل في مطعم معين
    public function getLoyaltyBalance(int $restaurantId): int
{
    // حساب النقاط المكتسبة
    $earned = $this->loyaltyTransactions()
        ->where('restaurant_id', $restaurantId)
        ->where('type', 'earn')
        ->sum('points');

    // حساب النقاط المستبدلة (المستخدمة)
    $redeemed = $this->loyaltyTransactions()
        ->where('restaurant_id', $restaurantId)
        ->where('type', 'redeem')
        ->sum('points');

    // الصافي المتاح للعميل
    return max(0, $earned - $redeemed);
}
}
