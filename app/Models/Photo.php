<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'race_number',
        'price',
        'stock_status',
        'downloadable',
        'update_date',
        'published_date',
       //'photo_type',
        'event_id',
        'category_id'
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Define a relationship to the Upload model
    public function upload()
    {
        return $this->hasMany(Upload::class, 'photo_id', 'id');
    }


    // Method to retrieve images where photo_type is 'regular' or 'lead_image'
    public function scopeImages($query) //$photos = Photo::images()->get();
    {
        return $query->whereHas('upload', function ($q) {
            $q->whereIn('photo_type', ['regular', 'lead_image']);
        });
    }

    public function regularImages(){

        return $this->upload()->wherePhotoType('regular')->get();
    }

    public function leadImage()
    {
        return $this->upload()->wherePhotoType('lead_image')->first();
    }

    public function leadImageLowResolution()
    {
        $lastDotPosition = strrpos($this->leadImage()?->file_path, '.');

        // Extract the base name and the extension
        $baseName = substr($this->leadImage()?->file_path, 0, $lastDotPosition);
        $extension = substr($this->leadImage()?->file_path, $lastDotPosition);
                    // Create the watermarked image path
        return  $baseName . '_200_300' . $extension;
    }

    public function leadImageWaterMark()
    {
        $lastDotPosition = strrpos($this->leadImage()?->file_path, '.');

        // Extract the base name and the extension
        $baseName = substr($this->leadImage()?->file_path, 0, $lastDotPosition);
        $extension = substr($this->leadImage()?->file_path, $lastDotPosition);
                    // Create the watermarked image path
        return  $baseName . '.watermark' . $extension;
        //return $this->upload()->wherePhotoType('lead_image')->first();
    }

    // Retrieve watermarked versions of regular images
    public function regularImagesWithWaterMark()
    {
        $regularImages = $this->regularImages();
        $watermarkedImages = [];

        foreach ($regularImages as $image) {
            $lastDotPosition = strrpos($image->file_path, '.');

            // Extract the base name and the extension
            $baseName = substr($image->file_path, 0, $lastDotPosition);
            $extension = substr($image->file_path, $lastDotPosition);

            // Create the watermarked image path
            $watermarkedImages[] = $baseName . '.watermark' . $extension;
        }

        return $watermarkedImages;
    }

}
