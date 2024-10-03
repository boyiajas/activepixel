<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt; // Import the Crypt facade
use App\Models\User;

class SubscriptionController extends Controller
{
    /**
     * Subscribe the user to receive updates.
     */
    public function subscribe($encoded)
    {
        try {
            // Decrypt the encoded string
            $decoded = Crypt::decryptString($encoded);
            list($userId, $name) = explode('|', $decoded); // Split the user ID and username

            // Find the user by ID
            $user = User::findOrFail($userId);

            // Update the optin field to true
            $user->optin = true;
            $user->save();

            // Redirect to the subscription success page
            return view('customer.subscribe', ['userEmail' => $user->email]);
        } catch (\Exception $e) {
            return redirect()->route('home')->withErrors(['msg' => 'Invalid subscription link.']);
        }
    }

    /**
     * Unsubscribe the user from receiving updates.
     */
    public function unsubscribe($encoded)
    {
        try {
            // Decrypt the encoded string
            $decoded = Crypt::decryptString($encoded);
            list($userId, $name) = explode('|', $decoded); // Split the user ID and username

            // Find the user by ID
            $user = User::findOrFail($userId);

            // Update the optin field to false
            $user->optin = false;
            $user->save();

            // Redirect to the unsubscribe success page
            return view('customer.unsubscribe', ['userEmail' => $user->email]);
        } catch (\Exception $e) {
            return redirect()->route('home')->withErrors(['msg' => 'Invalid unsubscribe link.']);
        }
    }
}
