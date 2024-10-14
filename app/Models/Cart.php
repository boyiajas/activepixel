<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'guest_token', 'photo_id', 'quantity', 'photo_type'];

    // Generate a guest token
    public static function generateGuestToken()
    {
        return Str::uuid();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function getTotalPriceAttribute()
    {
        
        return $this->photo->price * $this->quantity;
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
