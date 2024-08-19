<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function generateDownloadLink($photoId)
    {
        // Here you can use logic to generate a unique download link.
        return url('/download/' . uniqid($photoId . '_', true));
    }
}
