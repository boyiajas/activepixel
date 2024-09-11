<?php

namespace App\Jobs;

use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Intervention\Image\Facades\Image;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file_location;
    protected $photo_type;
    protected $directory, $filename, $extension;

    /**
     * Create a new job instance.
     */
    public function __construct($file_location, $photo_type, $directory, $filename, $extension)
    {
         // Convert URL to local filesystem path
        $this->file_location = $file_location;
        $this->photo_type = $photo_type;
        $this->directory = $directory;
        $this->filename = $filename;
        $this->extension = $extension;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info('Processing file at: ' . $this->file_location);
        // Wait for 5 seconds
        sleep(5);

        // Log the path again before the check
        Log::info('Checking file existence at: ' . $this->file_location);

        // Check if the file exists before processing
        if (!file_exists($this->file_location)) {
            Log::error('File not found: ' . $this->file_location);
            return;
        }

        try {
            $image = Image::make($this->file_location);

            switch ($this->photo_type) {
                case 'avatar':
                    $image->fit(200, 200)->save($this->file_location);
                    Log::info('Successfully resized avatar to 200x200 and saved to: ' . $this->file_location);
                    break;
                case 'lead_image':
                   
                    $image->fit(200, 300)->save($this->directory . $this->filename . '_200_300.' . $this->extension);
                    /* $image->fit(400, 161)->save($this->directory . $this->filename . '_400_161.' . $this->extension);
                    $image->fit(143, 83)->save($this->directory . $this->filename . '_143_83.' . $this->extension);
                    $image->fit(835, 467)->save($this->directory . $this->filename . '_835_467.' . $this->extension);
                    $image->fit(1920, 600)->save($this->directory . $this->filename . '_1920_600.' . $this->extension); */
                    //$image->fit(265, 163)->save($this->file_location);
                   /*  Log::info('Successfully resized lead image to 265x163 and saved to: ' . $this->file_location);
                    $image->fit(400, 161)->save($this->getVariantPath('_400_161'));
                    Log::info('Successfully resized lead image to 400x161 and saved to: ' . $this->getVariantPath('_400_161'));
                    $image->fit(143, 83)->save($this->getVariantPath('_143_83'));
                    Log::info('Successfully resized lead image to 143x83 and saved to: ' . $this->getVariantPath('_143_83'));
                    $image->fit(835, 467)->save($this->getVariantPath('_835_467'));
                    Log::info('Successfully resized lead image to 835x467 and saved to: ' . $this->getVariantPath('_835_467'));
                    $image->fit(1920, 600)->save($this->getVariantPath('_1920_600'));
                    Log::info('Successfully resized lead image to 1920x600 and saved to: ' . $this->getVariantPath('_1920_600')); */
                    break;
                case 'regular':
                    $image->fit(200, 300)->save($this->getVariantPath('_200_300'));
                    /* Log::info('Successfully resized regular image to 143x83 and saved to: ' . $this->getVariantPath('_143_83'));
                    $image->fit(835, 467)->save($this->getVariantPath('_835_467'));
                    Log::info('Successfully resized regular image to 835x467 and saved to: ' . $this->getVariantPath('_835_467')); */
                    break;
                case 'event_photo':
                    $image->fit(200, 300)->save($this->file_location);
                    Log::info('Successfully resized event photo to 265x163 and saved to: ' . $this->file_location);
                    /* $image->fit(800, 600)->save($this->file_location);
                    Log::info('Successfully resized event photo to 800x600 and saved to: ' . $this->file_location); */
                    break;
                case 'category_photo':
                    /*$image->fit(265, 163)->save($this->file_location);
                    Log::info('Successfully resized category photo to 265x163 and saved to: ' . $this->file_location);
                    $image->fit(400, 300)->save($this->file_location);
                    Log::info('Successfully resized category photo to 400x300 and saved to: ' . $this->file_location); */
                    break;
            }
            /* switch ($this->photo_type) {
                case 'avatar':
                    $image->fit(200, 200)->save($this->file_location);
                    break;
                case 'lead_image':
                    $image->fit(265, 163)->save($this->file_location);
                    $image->fit(400, 161)->save($this->getVariantPath('_400_161'));
                    $image->fit(143, 83)->save($this->getVariantPath('_143_83'));
                    $image->fit(835, 467)->save($this->getVariantPath('_835_467'));
                    $image->fit(1920, 600)->save($this->getVariantPath('_1920_600'));
                    break;
                case 'regular':
                    $image->fit(143, 83)->save($this->getVariantPath('_143_83'));
                    $image->fit(835, 467)->save($this->getVariantPath('_835_467'));
                    break;
                case 'event_photo':
                    $image->fit(265, 163)->save($this->file_location);
                    $image->fit(800, 600)->save($this->file_location);
                    break;
                case 'category_photo':
                    $image->fit(265, 163)->save($this->file_location);
                    $image->fit(400, 300)->save($this->file_location);
                    break;
            } */
        } catch (\Exception $e) {
            Log::error('Error processing image: ' . $e->getMessage());
        }
    }

    /**
     * Generate the variant file path based on the original.
     *
     * @return string
     */
    protected function getVariantPath($suffix)
    {
        $path_info = pathinfo($this->file_location);
        return $path_info['dirname'] . '/' . $path_info['filename'] . $suffix . '.' . $path_info['extension'];
    }

        /* $file_location = $this->upload->file_path;
        $directory = pathinfo($file_location, PATHINFO_DIRNAME);
        $filename = pathinfo($file_location, PATHINFO_FILENAME);
        $extension = $this->upload->extension;

        switch ($this->upload->photo_type) {
            case 'avatar':
                Image::make($file_location)->fit(200, 200)->save($file_location);
                break;
            case 'lead_image':
                Image::make($file_location)->fit(265, 163)->save($directory . '/' . $filename . '_265_163.' . $extension);
                Image::make($file_location)->fit(400, 161)->save($directory . '/' . $filename . '_400_161.' . $extension);
                Image::make($file_location)->fit(143, 83)->save($directory . '/' . $filename . '_143_83.' . $extension);
                Image::make($file_location)->fit(835, 467)->save($directory . '/' . $filename . '_835_467.' . $extension);
                Image::make($file_location)->fit(1920, 600)->save($directory . '/' . $filename . '_1920_600.' . $extension);
                break;
            case 'regular':
                Image::make($file_location)->fit(143, 83)->save($directory . '/' . $filename . '_143_83.' . $extension);
                Image::make($file_location)->fit(835, 467)->save($directory . '/' . $filename . '_835_467.' . $extension);
                break;
            case 'event_photo':
                Image::make($file_location)->fit(265, 163)->save($directory . '/' . $filename . '_265_163.' . $extension);
                Image::make($file_location)->fit(800, 600)->save($file_location);
                break;
            case 'category_photo':
                Image::make($file_location)->fit(265, 163)->save($directory . '/' . $filename . '_265_163.' . $extension);
                Image::make($file_location)->fit(400, 300)->save($file_location);
                break;
        } */
    
}
