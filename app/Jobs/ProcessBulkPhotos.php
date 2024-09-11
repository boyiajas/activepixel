<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;
use App\Models\Photo;
use App\Models\Upload;
use Illuminate\Support\Str;
use File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProcessBulkPhotos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 10800;// 1hour 3600, 3hour 10800

    protected $zipFilePath;
    protected $eventId;
    protected $photoAction;
    protected $photoType;

    public function __construct($zipFilePath, $eventId, $photoAction, $photoType)
    {
        $this->zipFilePath = $zipFilePath;
        $this->eventId = $eventId;
        $this->photoAction = $photoAction;
        $this->photoType = $photoType;

        \Log::info("Job initialized with Event ID: {$this->eventId}, ZIP file path: {$this->zipFilePath}, Action: {$this->photoAction}, Photo Type: {$this->photoType}");
    }

    public function handle()
    {
        $zip = new ZipArchive;
        if ($zip->open($this->zipFilePath) === true) {
            \Log::info("Successfully opened the ZIP file: {$this->zipFilePath}");

            $extractPath = storage_path('app/public/temp/' . uniqid());
            File::makeDirectory($extractPath, 0755, true, true);
            \Log::info("Extracting ZIP file to: {$extractPath}");

            try {
                $zip->extractTo($extractPath);
                $zip->close();

                $directories = File::directories($extractPath);
                \Log::info("Found directories: " . implode(', ', $directories));

                if (count($directories) === 1) {
                    $parentDirectory = $directories[0];
                    $parentDirectoryName = basename($parentDirectory);
                    \Log::info("Parent directory name: {$parentDirectoryName}");

                    list($price, $description) = explode('_', $parentDirectoryName, 2);
                    \Log::info("Extracted price: {$price}, description: {$description}");

                    $imageFiles = File::files($parentDirectory);
                    \Log::info("Found image files: " . implode(', ', array_map(fn($file) => $file->getFilename(), $imageFiles)));

                    foreach ($imageFiles as $image) {
                        $raceNumber = pathinfo($image->getFilename(), PATHINFO_FILENAME);
                        $extension = strtolower($image->getExtension());

                        \Log::info("Processing image: {$image->getFilename()} with race number: {$raceNumber}, extension: {$extension}");

                        $photos = Photo::where('race_number', $raceNumber)
                            ->where('event_id', $this->eventId)
                            ->get();

                        if ($photos->isEmpty()) {
                            // Create a new photo if none exist
                            $photo = Photo::create([
                                'name' => $raceNumber,
                                'race_number' => $raceNumber,
                                'price' => $price,
                                'description' => $description,
                                'event_id' => $this->eventId,
                                'stock_status' => 'in_stock',
                                'downloadable' => true,
                            ]);
                            $photos = collect([$photo]); // Convert to collection for consistency
                        }

                        foreach ($photos as $photo) {
                            // Check if an upload already exists for this photo and photo_type
                            $existingUpload = Upload::where('photo_id', $photo->id)
                                ->where('photo_type', $this->photoType)
                                ->first();

                            switch ($this->photoAction) {
                                case 'skip':
                                    if ($existingUpload) {
                                        \Log::info("Skipping image with race number: {$raceNumber} as it already exists.");
                                        continue 2; // Skip to the next image
                                    }
                                    break;

                                case 'replace':
                                    if ($existingUpload) {
                                        $existingUpload->delete();
                                        \Log::info("Deleted existing upload for photo with race number: {$raceNumber}");
                                    }
                                    break;

                                case 'duplicate':
                                    // Duplicate logic here (if needed)
                                    break;

                                default:
                                    \Log::warning("Unknown action for photo with race number: {$raceNumber}");
                                    continue 2;
                            }

                            // Save the new upload
                            $photoId = $photo->id;

                            $directory = 'public/uploads/photos/' . $photoId . '/';
                            $directoryToSave = 'uploads/photos/' . $photoId . '/';
                            $filename = $this->photoType . '_' . Str::random(12) . '.' . $extension;

                            if (!File::exists($directory)) {
                                File::makeDirectory($directory, 0755, true);
                                \Log::info("Directory did not exist and was created: {$directory}");
                            } else {
                                \Log::info("Directory already exists: {$directory}");
                            }

                            \Log::info("Moving file to: {$directory}{$filename}");
                            File::move($image->getPathname(), $directory . $filename);

                            $imagePath = $directory . $filename;
                            $imageInstance = Image::make($imagePath);

                            $imageInstance->fit(200, 300)->save($directory . pathinfo($filename, PATHINFO_FILENAME) . '_200_300.' . $extension);

                            // Apply watermark and save as a new file
                            $watermarkImagePath = $directory . pathinfo($filename, PATHINFO_FILENAME) . '.watermark.' . $extension;
                            $watermarkResizeImagePath = $directory . pathinfo($filename, PATHINFO_FILENAME) . '.watermark_200_300.' . $extension;
                            $this->applyWatermark($imageInstance, $watermarkImagePath, $watermarkResizeImagePath);

                            
                            /* $imageInstance->fit(400, 161)->save($directory . $filename . '_400_161.' . $extension); */

                            Upload::create([
                                'photo_id' => $photoId,
                                'photo_type' => $this->photoType,
                                'file_name' => $filename,
                                'file_path' => $directoryToSave . $filename,
                                'extension' => $extension,
                            ]);
                        }
                    }

                    File::deleteDirectory($extractPath);
                    \Log::info("Completed processing bulk photos and cleaned up temporary files.");

                } else {
                    throw new \Exception('Unexpected directory structure inside the ZIP file.');
                }
            } catch (\Exception $e) {
                \Log::error('Error processing bulk photos: ' . $e->getMessage());
            } finally {
                if (File::exists($this->zipFilePath)) {
                    File::delete($this->zipFilePath);
                    \Log::info("Deleted original ZIP file: {$this->zipFilePath}");
                }

                $this->cleanupChunks();
            }
        } else {
            \Log::error("Unable to open ZIP file for processing: {$this->zipFilePath}");
        }
    }

    protected function applyWatermark($imageInstance, $watermarkImagePath, $watermarkResizeImagePath)
    {
        $watermark = Image::make(public_path('assets/img/watermark.png'));

        // Get the dimensions of the original image
        $originalWidth = $imageInstance->width();
        $originalHeight = $imageInstance->height();

        // Calculate new watermark size (80% of original image width)
        $watermarkWidth = $originalWidth * 0.9;
        $watermarkHeight = ($watermarkWidth / $watermark->width()) * $watermark->height();

        // Resize the watermark
        $watermark->resize($watermarkWidth, $watermarkHeight);

        // Apply the watermark at the center of the image
        $imageInstance->insert($watermark, 'center')->save($watermarkImagePath);

        // Now, instead of reapplying the watermark, just resize the already watermarked image
        $resizedImage = Image::make($watermarkImagePath); // Load the watermarked image
        $resizedImage->fit(200, 300)->save($watermarkResizeImagePath); // Resize and save the 200x300 image

    }

    protected function cleanupChunks()
    {
        $chunkDir = dirname($this->zipFilePath);
        if (File::isDirectory($chunkDir)) {
            File::deleteDirectory($chunkDir);

            \Log::info("Deleted chunk directory: {$chunkDir}");
        }
    }
}


