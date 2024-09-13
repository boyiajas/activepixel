<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DigitalDownload extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'photo_id', 'download_link', 'expiry_date'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    /* public static function generateDownloadLink($photoId)
    {
        // Here you can use logic to generate a unique download link.
        return url('/download/' . uniqid($photoId . '_', true));
    } */

    public static function generateDownloadLink($cartItems)
    {
        $links = [];

        foreach ($cartItems as $item) {
            $photo = $item->photo;

            // Get the lead image and regular images for each photo
            $leadImage = $photo->leadImage(); // Lead image
            $regularImages = $photo->regularImages(); // Regular images

            // Add lead image download link (if available)
            if ($leadImage) {
                $links[] = url($leadImage->file_path);  // generate a public link
            }

            // Add regular image download links (if available)
            foreach ($regularImages as $regularImage) {
                $links[] = url($regularImage->file_path); // generate a public link
            }
        }

        return $links;
    }

}
