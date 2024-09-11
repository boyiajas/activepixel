<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Jobs\ProcessImage;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function storeImage(Request $request)
    {
        $photo = $request->file('file');
        $photo_type = $request->photo_type;
        $photo_id = $request->photo_id;
        $result = Photo::find($photo_id);

        // Validate file upload
        if (!$result) {
            return response()->json('Invalid file upload', 400);
        }

        $photo_name = $photo->getClientOriginalName();
        $extension = strtolower($photo->getClientOriginalExtension());
        $userId = Auth::user()->id;

        // Define directory and filename based on photo_type
        switch ($photo_type) {
            case 'avatar':
                $directory = 'uploads/users/avatar/';
                $filename = 'userAvatar_' . Str::random(12);
                break;
            case 'lead_image':
                $directory = 'uploads/photos/' . $userId . '/';
                $filename = 'lead_image_' . strtolower(str_replace(' ', '-', $photo_name)) . '_' . Str::random(12);
                break;
            case 'regular':
                $directory = 'uploads/photos/' . $userId . '/';
                $filename = 'image_' . strtolower(str_replace(' ', '-', $photo_name)) . '_' . Str::random(12);
                break;
            default:
                $directory = 'uploads/misc/';
                $filename = 'misc_' . Str::random(12);
                break;
        }

        $file_location = $directory . $filename . '.' . $extension;

        // Ensure directory exists
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Check if photo_type is lead_image and if an existing lead image for the same photo_id exists
        if ($photo_type === 'lead_image') {
            $existingUpload = Upload::where('photo_id', $photo_id)->where('photo_type', 'lead_image')->first();

            if ($existingUpload) {
                // Get full path of the existing image
                $originalFilePath = public_path($existingUpload->file_path);

                // Extract directory and filename from the existing image
                $existingDirectory = pathinfo($originalFilePath, PATHINFO_DIRNAME);
                $existingFilename = pathinfo($originalFilePath, PATHINFO_FILENAME);
                $existingExtension = $existingUpload->extension;

                // Define sizes used for lead_image
                $sizes = ['_200_300','.watermark_200_300'];

                // Delete resized images
                foreach ($sizes as $size) {
                    $sizedFilePath = $existingDirectory . '/' . $existingFilename . $size . '.' . $existingExtension;
                    if (File::exists($sizedFilePath)) {
                        File::delete($sizedFilePath);
                    }
                }

                // Delete the watermark file
                $watermarkFilePath = $existingDirectory . '/' . $existingFilename . '.watermark.' . $existingExtension;
                if (File::exists($watermarkFilePath)) {
                    File::delete($watermarkFilePath);
                }

                // Delete the original image file
                if (File::exists($originalFilePath)) {
                    File::delete($originalFilePath);
                }

                // Update the existing upload record
                $existingUpload->file_name = $photo_name;
                $existingUpload->file_path = $file_location;
                $existingUpload->extension = $extension;
                $existingUpload->save();
            } else {
                // Create a new upload record if no existing lead_image
                $existingUpload = Upload::create([
                    'photo_id' => $photo_id,
                    'photo_type' => $photo_type,
                    'file_name' => $photo_name,
                    'file_path' => $file_location,
                    'extension' => $extension,
                ]);
            }

        } else {
            // Handle regular image upload
            Upload::create([
                'photo_id' => $photo_id,
                'photo_type' => $photo_type,
                'file_name' => $photo_name,
                'file_path' => $file_location,
                'extension' => $extension,
            ]);
        }

        // Move the file to the appropriate directory
        $upload_success = $photo->move($directory, $filename . '.' . $extension);

        if ($upload_success) {
            $image = Image::make($file_location);
            // Resize and process image
            switch ($photo_type) {
                case 'avatar':
                    $image->fit(200, 200)->save($file_location);
                    break;
                case 'lead_image':
                    $image->fit(200, 300)->save($directory . $filename . '_200_300.' . $extension);

                    $watermarkResizeImagePath = $directory . $filename . '.watermark_200_300.' . $extension;
                    $this->applyWatermark($file_location, $directory, $filename, $extension, $watermarkResizeImagePath);
                    break;
                case 'regular':
                    $image->fit(200, 300)->save($directory . $filename . '_200_300.' . $extension);
                    break;
            }

            return response()->json("Successfully upload file", 200);
        } else {
            return response()->json('Error uploading file', 400);
        }
    }

    protected function applyWatermark($imagePath, $directory, $filename, $extension, $watermarkResizeImagePath)
    {
        // Load the original image
        $originalImage = Image::make($imagePath);

        // Get the dimensions of the original image
        $originalWidth = $originalImage->width();
        $originalHeight = $originalImage->height();

        // Load the watermark image
        $watermarkImagePath = public_path('assets/img/watermark.png');
        $watermark = Image::make($watermarkImagePath);

        // Calculate new watermark size (80% of original image width)
        $watermarkWidth = $originalWidth * 0.9;
        $watermarkHeight = ($watermarkWidth / $watermark->width()) * $watermark->height();

        // Resize the watermark
        $watermark->resize($watermarkWidth, $watermarkHeight);

        // Apply the watermark to the center of the original image
        $originalImage->insert($watermark, 'center');

        // Save the watermarked image
        $watermarkedImagePath = $directory . $filename . '.watermark.' . $extension;
        $originalImage->save($watermarkedImagePath);

        // Now, instead of reapplying the watermark, just resize the already watermarked image
        $resizedImage = Image::make($watermarkImagePath); // Load the watermarked image
        $resizedImage->fit(200, 300)->save($watermarkResizeImagePath); // Resize and save the 200x300 image

        //return $watermarkedImagePath;
    }
    /**
     * Display the specified resource.
     */
    public function show(Upload $upload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Upload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Upload $upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteImage(Request $request)
    {
        $userId = Auth::user()->id;

        $file = Upload::find($request->id);

        if ($file === null) { // Check if the file with the given ID exists
            return response()->json('File not found', 404);
        }

        // Get the full path to the original file
        $originalFilePath = public_path($file->file_path);

        // Extract the directory and base filename without the size suffix and extension
        $directory = pathinfo($originalFilePath, PATHINFO_DIRNAME);
        $filename = pathinfo($originalFilePath, PATHINFO_FILENAME);  // Filename without extension
        $extension = $file->extension;

        // Define the different image sizes used in the storeImage function
        $sizes = ['_200_300','.watermark_200_300', '.watermark'];

        // Delete the resized images
        foreach ($sizes as $size) {
            $sizedFilePath = $directory . '/' . $filename . $size . '.' . $extension; \Log::info("resize path : {$sizedFilePath}");
            if (File::exists($sizedFilePath)) {
                File::delete($sizedFilePath);
            }
        }

        // Delete the original image file
        if (File::exists($originalFilePath)) { \Log::info("resize path : {$originalFilePath}");
            File::delete($originalFilePath);
        }

        // Check if the directory is empty and delete it if it is
        if (is_dir($directory) && count(glob($directory . '/*')) === 0) {
            \Log::info("Deleting empty directory: {$directory}");
            rmdir($directory);
        }

        // Delete the record from the database
        //$file->delete();

        return response()->json('File deleted successfully', 200);
    }


}
