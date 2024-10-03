<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt; // Import the Crypt facade

class EmailPreviewController extends Controller
{
    public function purchaseEmailPreview()
    {
        // Dummy data for preview
        $dummyData = [
            'name' => 'John Doe',
            'downloadLinks' => [
                'https://example.com/photo1.jpg',
                'https://example.com/photo2.jpg',
            ],
            'order_number' => '123456789',
            'total_cost' => '49.99',
        ];

        // Encode user ID and username for subscription links
        $userId = 1; // Example user ID (replace with a real ID if necessary)
        $username = 'John Doe'; // Example username (replace with a real username if necessary)
        
        // Create encoded links for subscription and unsubscription
        $encodedSubscribeLink = Crypt::encryptString($userId . '|' . $username);
        $encodedUnsubscribeLink = Crypt::encryptString($userId . '|' . $username);

        // Add encoded links to the dummy data
        $dummyData['subscribeLink'] = route('subscribe', ['encoded' => $encodedSubscribeLink]);
        $dummyData['unsubscribeLink'] = route('unsubscribe', ['encoded' => $encodedUnsubscribeLink]);

        // Render the email view with dummy data
        return view('emails.purchase')->with($dummyData);
    }
}