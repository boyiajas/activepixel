<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'payment_method_id',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

     // Accessor to get customer name directly
     public function getCustomerNameAttribute()
     {
         return $this->user ? $this->user->name : 'Guest';
     }
 
     // Accessor to get payment method name directly
     public function getPaymentMethodNameAttribute()
     {
         return $this->paymentMethod ? $this->paymentMethod->name : 'Not Specified';
     }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
