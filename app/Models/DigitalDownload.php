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

    public static function generateDownloadLink($cartItems)
    {
        $links = [];

        foreach ($cartItems as $item) {
            $photo = $item->photo;

            // Check the photo_type in the cart item
            if ($item->photo_type === 'lead_image') {
                // Get the lead image download link
                $leadImage = $photo->leadImage();
                if ($leadImage) {
                    // Generate a download link using the download route, passing both photo_id and the file name
                    $links[] = route('downloadFile', ['photo_id' => $photo->id, 'filePath' => $leadImage->file_path]);
                }
            } elseif ($item->photo_type === 'regular_image') {
                // Get the regular image download link (assuming there could be multiple regular images)
                $regularImages = $photo->regularImages();
                if ($regularImages->isNotEmpty()) {
                    foreach ($regularImages as $regularImage) {
                        // Generate a download link using the download route, passing both photo_id and the file name
                        $links[] = route('downloadFile', ['photo_id' => $photo->id, 'filePath' => $regularImage->file_path]);
                    }
                }
            }
        }

        return $links;
    }


}
