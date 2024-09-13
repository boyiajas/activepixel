<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchasedPhotosEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $downloadLinks;
    public $order_number;
    public $total_cost;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $downloadLinks, $order_number, $total_cost)
    {
        $this->name = $name;
        $this->downloadLinks = $downloadLinks;
        $this->order_number = $order_number;
        $this->total_cost = $total_cost;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        $mail = $this->view('emails.purchase')
                    ->subject('Your Photo Purchase')
                    ->with([
                        'name' => $this->name,
                        'downloadLinks' => $this->downloadLinks,
                        'order_number' => $this->order_number,
                        'total_cost' => $this->total_cost,
                    ]);

      

        return $mail;
    }
}
