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
                    break;
                case 'regular':
                    $image->fit(143, 83)->save($directory . $filename . '_143_83.' . $extension);
                    $image->fit(835, 467)->save($directory . $filename . '_835_467.' . $extension);
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
