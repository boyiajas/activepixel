<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

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
        $photoType = $request->photo_type ?? 'lead_image'; // Default to 'lead_image' if not provided
        $quantity = $request->quantity ?? 1;

        try {
            if (Auth::check()) {
                // Check if the item with the same photo_id and photo_type already exists in the cart
                $cartItem = Cart::where('user_id', Auth::id())
                                ->where('photo_id', $photoId)
                                ->where('photo_type', $photoType)
                                ->first();
            } else {
                // User is a guest
                $guestToken = session('guest_token', Cart::generateGuestToken());
                session(['guest_token' => $guestToken]);

                // Check if the item with the same photo_id and photo_type already exists in the cart for the guest
                $cartItem = Cart::where('guest_token', $guestToken)
                                ->where('photo_id', $photoId)
                                ->where('photo_type', $photoType)
                                ->first();
            }

            if ($cartItem) {
                // If the item already exists, we don't increment the quantity
                Toastr::info('This item is already in your cart.','Info');
            } else {
                // Create a new cart item
                if (Auth::check()) {
                    $cartItem = Cart::create([
                        'user_id' => Auth::id(),
                        'photo_id' => $photoId,
                        'photo_type' => $photoType,
                        'quantity' => $quantity
                    ]);
                } else {
                    $cartItem = Cart::create([
                        'guest_token' => $guestToken,
                        'photo_id' => $photoId,
                        'photo_type' => $photoType,
                        'quantity' => $quantity
                    ]);
                }

                Toastr::success('Item added to cart.', 'Success');
            }

            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error('Failed to add item to cart.', 'Error');
            return redirect()->back();
        }
    }


    public function removeFromCart(Request $request, Cart $cart)
    {
        try{
            if($cart->quantity > 1){
                $cart->quantity -= 1;
                $cart->save();
            }else{  
                $cart->delete();
            }

            Toastr::success('One Item deleted from cart.','Success');
            return redirect()->back();

        } catch (\Throwable $th) {
            Toastr::error('Item deleted from cart fail :)','Error');
            return redirect()->back();
        }
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
