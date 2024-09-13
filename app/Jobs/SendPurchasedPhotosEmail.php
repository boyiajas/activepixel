<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchasedPhotosEmail;

class SendPurchasedPhotosEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $downloadLinks;
    public $order_number;
    public $total_cost;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $downloadLinks, $order_number, $total_cost)
    {
        $this->user = $user;
        $this->downloadLinks = $downloadLinks;
        $this->order_number = $order_number;
        $this->total_cost = $total_cost;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->user->email)
            ->send(new PurchasedPhotosEmail($this->user->name, $this->downloadLinks, $this->order_number, $this->total_cost
        ));
    }
}
