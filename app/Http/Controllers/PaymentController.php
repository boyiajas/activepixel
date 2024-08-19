<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use Brian2694\Toastr\Facades\Toastr;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Handle successful payment.
     */
    public function success(Request $request)
    {
        
        Toastr::success('Payment successful! Thank you for your order.','Success');

        return view('payments.success');
    }

    /**
     * Handle canceled payment.
     */
    public function cancel(Request $request)
    {
        
        Toastr::error('Payment was canceled. Please try again :) ' ,'Error');
        
        return view('payments.cancel');
    }

    /**
     * Handle PayFast notification.
     */
    public function notify(Request $request)
    {
        // PayFast sends a POST request with the payment details
        $payfastData = $request->all();

        // Retrieve the order based on the payment ID (m_payment_id)
        $order = Order::where('order_number', $payfastData['m_payment_id'])->first();

        // Verify the signature (and other checks if necessary)
        $signature = $this->generateSignature($payfastData);

        if ($signature !== $payfastData['signature']) {
            // Log a security alert or notify the admin
            return response('Invalid signature', 400);
        }

        if ($order) {
            // Verify the PayFast payment status
            if ($payfastData['payment_status'] == 'COMPLETE') {
                // Update order status to paid
                $order->status = 'paid';
                $order->save();

                 // Optionally, clear the user's cart after successful payment
                Cart::where('user_id', $order->user_id)->delete();

            } else if ($payfastData['payment_status'] == 'FAILED') {
                // Update order status to failed
                $order->status = 'failed';
                $order->save();
            }
        }

        // Return a 200 OK response to acknowledge the notification
        return response()->json(['status' => 'success'], 200);
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
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
