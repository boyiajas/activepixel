<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class PurchasedPhotosEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $downloadLinks;
    public $order_number;
    public $total_cost;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $downloadLinks, $order_number, $total_cost)
    {
        $this->name = $user->name;
        $this->downloadLinks = $downloadLinks;
        $this->order_number = $order_number;
        $this->total_cost = $total_cost;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        // Create encoded links for subscription and unsubscription
        $encodedSubscribeLink = Crypt::encryptString($this->user->id . '|' . $this->user->name);
        $encodedUnsubscribeLink = Crypt::encryptString($this->user->id . '|' . $this->user->name);

        $mail = $this->view('emails.purchase')
                    ->subject('Your Photo Purchase')
                    ->with([
                        'name' => $this->name,
                        'downloadLinks' => $this->downloadLinks,
                        'order_number' => $this->order_number,
                        'total_cost' => $this->total_cost,
                        'subscribeLink' => route('subscribe', ['encoded' => $encodedSubscribeLink]),
                        'unsubscribeLink' => route('unsubscribe', ['encoded' => $encodedUnsubscribeLink]),
                    ]);

      

        return $mail;
    }
}
