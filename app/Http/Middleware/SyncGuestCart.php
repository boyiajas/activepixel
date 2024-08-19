<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class SyncGuestCart
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $guestToken = session('guest_token');
            if ($guestToken) {
                $guestCart = Cart::where('guest_token', $guestToken)->get();

                foreach ($guestCart as $cartItem) {
                    $existingCartItem = Cart::where('user_id', Auth::id())
                        ->where('photo_id', $cartItem->photo_id)
                        ->first();

                    if ($existingCartItem) {
                        $existingCartItem->quantity += $cartItem->quantity;
                        $existingCartItem->save();
                    } else {
                        $cartItem->user_id = Auth::id();
                        $cartItem->guest_token = null;
                        $cartItem->save();
                    }
                }
            }
            session()->forget('guest_token');
        }

        return $next($request);
    }
}

