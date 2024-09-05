<?php

namespace App\Http\Controllers;

use App\Models\Upload;
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

        // Validate file upload
        if (!$photo->isValid()) {
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
                $sizes = ['_143_83', '_265_163', '_400_161', '_835_467', '_1920_600'];

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
                    $image->fit(265, 163)->save($directory . $filename . '_265_163.' . $extension);
                    $image->fit(400, 161)->save($directory . $filename . '_400_161.' . $extension);
                    $image->fit(143, 83)->save($directory . $filename . '_143_83.' . $extension);

                    $this->applyWatermark($file_location, $directory, $filename, $extension);
                    break;
                case 'regular':
                    $image->fit(143, 83)->save($directory . $filename . '_143_83.' . $extension);
                    $image->fit(835, 467)->save($directory . $filename . '_835_467.' . $extension);
                    break;
            }

            return response()->json("Successfully upload file", 200);
        } else {
            return response()->json('Error uploading file', 400);
        }
    }

    public function storeImageOld(Request $request)
    {
        $photo = $request->file('file');
        $photo_type = $request->photo_type;
        $photo_id = $request->photo_id;

        // Validate file upload
        if (!$photo->isValid()) {
            return response()->json('Invalid file upload', 400);
        }

        $photo_name = $photo->getClientOriginalName();

        $extension = strtolower($photo->getClientOriginalExtension());
        $userId = Auth::user()->id;

        switch ($photo_type){

            case 'avatar':  // Naming Scheme for User Avatar
                $directory = 'uploads/users/avatar/';
                $filename = 'userAvatar_' . Str::random(12);
                break;
            case 'lead_image':
                $directory = 'uploads/photos/'.$userId. '/';
                $filename = 'lead_image_'. strtolower(str_replace(' ', '-', $photo_name)) . '_' . Str::random(12);
                break;
            case 'regular':
                $directory = 'uploads/photos/'.$userId.'/';
                $filename = 'image_'. strtolower(str_replace(' ', '-', $photo_name)) . '_' . Str::random(12); 
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

        $upload_success = $photo->move($directory, $filename . '.' . $extension);

        if ($upload_success) {

            // Save the file details using the Upload model
            $upload = Upload::create([
                'photo_id' => $photo_id,
                'photo_type' => $photo_type,
                'file_name' => $photo_name,
                'file_path' => $file_location,
                'extension' => $extension,
            ]);
            \Log::info(''. $photo_id .''. $photo_type .print_r($upload));
            //here we getting the base url and adding it to the file location
            //$full_file_location = urlToPath($file_location);

            // Dispatch the image processing job to the queue
            //ProcessImage::dispatch($file_location, $photo_type, $directory, $filename, $extension);
            $image = Image::make($file_location);
            // Resize and process image according to its type
            switch ($photo_type) {
                case 'avatar':
                    $image->fit(200, 200)->save($file_location);
                    break;
                case 'lead_image':
                    $image->fit(265, 163)->save($directory . $filename . '_265_163.' . $extension);
                    $image->fit(400, 161)->save($directory . $filename . '_400_161.' . $extension);
                    $image->fit(143, 83)->save($directory . $filename . '_143_83.' . $extension);
                    //$image->fit(835, 467)->save($directory . $filename . '_835_467.' . $extension);
                    //$image->fit(1920, 600)->save($directory . $filename . '_1920_600.' . $extension);

                    // Apply watermark and save as a new file
                    //$this->applyWatermark($image, $directory, $filename, $extension);
                    $this->applyWatermark($file_location, $directory, $filename, $extension);
                    break;
                case 'regular':
                    $image->fit(143, 83)->save($directory . $filename . '_143_83.' . $extension);
                    $image->fit(835, 467)->save($directory . $filename . '_835_467.' . $extension);

                    // Apply watermark and save as a new file
                    /* $watermarkImagePath = $directory . $filename . '.watermark.' . $extension;
                    $this->applyWatermark($image, $watermarkImagePath); */
                    break;
                /* case 'event_photo':
                    $image->fit(265, 163)->save($directory . $filename . '_265_163.' . $extension);
                    //$image->fit(800, 600)->save($file_location);
                    break;
                case 'category_photo':
                    $image->fit(265, 163)->save($directory . $filename . '_265_163.' . $extension);
                    $image->fit(400, 300)->save($file_location);
                    break; */
            } 

            return response()->json($upload);
        } else {
            return response()->json('Error uploading file', 400);
        }
    }

   /*  protected function applyWatermark($imageInstance, $watermarkImagePath)
    {
        $watermark = Image::make(public_path('assets/img/watermark.png'));

        // Apply the watermark at the center of the image
        $imageInstance->insert($watermark, 'center')->save($watermarkImagePath);
    } */

    protected function applyWatermark($imagePath, $directory, $filename, $extension)
    {
        /* $originalImage = Image::make($imagePath);
        $watermarkImagePath = public_path('assets/img/watermark.png');

        // Load the watermark image
        $watermark = Image::make($watermarkImagePath);

        // Apply the watermark to the center of the original image
        $originalImage->insert($watermark, 'center');

        // Save the watermarked image
        $watermarkedImagePath = $directory . $filename . '.watermark.' . $extension;
        $originalImage->save($watermarkedImagePath);

        return $watermarkedImagePath; */
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
        $sizes = ['_143_83', '_265_163', '_400_161', '_835_467', '_1920_600'];

        // Delete the resized images
        foreach ($sizes as $size) {
            $sizedFilePath = $directory . '/' . $filename . $size . '.' . $extension;
            if (File::exists($sizedFilePath)) {
                File::delete($sizedFilePath);
            }
        }

        // Delete the original image file
        if (File::exists($originalFilePath)) {
            File::delete($originalFilePath);
        }

        // Delete the record from the database
        $file->delete();

        return response()->json('File deleted successfully', 200);
    }


}
