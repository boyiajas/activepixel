<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartItems = []; 
        $totalPrice = 0;

        if (Auth::check()) {
            // Get cart items for logged-in user
            $cartItems = Cart::where('user_id', Auth::id())->get();
        } else {
            // Get cart items for guest user
            $guestToken = session('guest_token');
            if ($guestToken) {
                $cartItems = Cart::where('guest_token', $guestToken)->get();
            }
        }

        // Calculate the total price of the cart
        foreach ($cartItems as $item) {
            $totalPrice += $item->total_price;
        }
       
        
        return view("cart", compact('cartItems', 'totalPrice'));
    }

   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function addToCart(Request $request)
    {
        $photoId = $request->photo_id;
        $quantity = $request->quantity ?? 1;

        if (Auth::check()) {
            // User is logged in
            $cartItem = Cart::firstOrCreate([
                'user_id' => Auth::id(),
                'photo_id' => $photoId,
            ]);
        } else {
            // User is a guest
            $guestToken = session('guest_token', Cart::generateGuestToken());
            session(['guest_token' => $guestToken]);

            $cartItem = Cart::firstOrCreate([
                'guest_token' => $guestToken,
                'photo_id' => $photoId,
            ]);
        }

        $cartItem->quantity += $quantity;
        $cartItem->save();

        return redirect()->back()->with('success', 'Item added to cart.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
