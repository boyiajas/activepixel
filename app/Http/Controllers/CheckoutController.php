<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Checkout;
use Illuminate\Http\Request;
use App\Models\Cart;
use Brian2694\Toastr\Facades\Toastr;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartItems = [];
        $totalPrice = 0;
        $billingInformation = [];

        if (Auth::check()) {
            // Get cart items for logged-in user
            // Ensure the user is authenticated
            $user = Auth::user();
            $cartItems = Cart::where('user_id', $user->id)->get();
           
            // If it's already an array, use it as is
            $billingInformation = $user->billing_information;
            
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

        return view("checkout", compact('cartItems', 'totalPrice','billingInformation'));
    }

    public function process(Request $request)
    {
        //dd($request->all());
        try{

            $user = Auth::user();

            // Validate the billing information
            $request->validate([
                'billing_first_name' => 'required|string|max:255',
                'billing_last_name' => 'required|string|max:255',
                'billing_email' => 'required|string|max:255',
                'billing_phone' => 'required|string|max:20',
                'billing_address' => 'required|string|max:255',
                'billing_city' => 'required|string|max:255',
                'billing_state' => 'required|string|max:255',
                'billing_zip' => 'required|string|max:20',
                'billing_country' => 'required|string|max:255',
            ]);

            // Save billing information in the user's profile
            $billingInfo = $request->only([
                'billing_first_name',
                'billing_last_name',
                'billing_email',
                'billing_phone',
                'billing_address',
                'billing_city',
                'billing_state',
                'billing_zip',
                'billing_country'
            ]);
            $user->billing_information = $billingInfo;
            $user->save();

            // Calculate total price
            $cartItems = Cart::where('user_id', $user->id)->get();
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->photo->price * $item->quantity;
            });

            // Prepare data for PayFast payment
            $payfastData = [
                'merchant_id' => env('PAYFAST_MERCHANT_ID'),
                'merchant_key' => env('PAYFAST_MERCHANT_KEY'),
                'return_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
                'notify_url' => route('payment.notify'),
                'name_first' => $user->name,
                'email_address' => $user->email,
                'm_payment_id' => uniqid(), // Unique ID for this payment
                'amount' => number_format($totalAmount, 2, '.', ''), // Amount in the correct format
                'item_name' => 'Order from ' . config('app.name'),
            ];

            // Generate the PayFast signature
            $signature = $this->generateSignature($payfastData, env('PAYFAST_PASSPHRASE'));
            $payfastData['signature'] = $signature;
            

            // If in testing mode make use of either sandbox.payfast.co.za or www.payfast.co.za
            $testingMode = true;
            $pfHost = $testingMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';

            // Redirect to PayFast payment page
            $payfastUrl = 'https://'.$pfHost.'/eng/process?' . http_build_query($payfastData);
            
            return redirect()->away($payfastUrl);

        } catch (\Exception $e) {
            //dd($e->getMessage());
            Toastr::error('Update record failed :) '.$e->getMessage(),'Error');
            return redirect()->back();
        }
    }

    private function generateSignature($data, $passPhrase = null) {
        // Create parameter string
        $pfOutput = '';
        foreach( $data as $key => $val ) {
            if($val !== '') {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );
        if( $passPhrase !== null ) {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }
        return md5( $getString );
    } 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Checkout $checkout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checkout $checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Checkout $checkout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkout $checkout)
    {
        //
    }
}
