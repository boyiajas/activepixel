<?php


namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function downloadFile($photo_id, $filePath)
    {
        // Retrieve the photo based on the provided photo_id
        $photo = Photo::find($photo_id); 
       

        // Check if the photo exists
        if (!$photo) {
            return abort(404, 'Photo not found');
        }

        // Construct the file path based on the photo_id and file name
        $fileFullPath = public_path($filePath);

        // Check if the file exists in the file system
        if (!file_exists($fileFullPath)) {
            return abort(404, 'File not found');
        }

        // Clean the output buffer to prevent file corruption
        if (ob_get_length()) {
            ob_end_clean();
        }
        // Return the file as a download response
        return response()->download($fileFullPath);
    }
}


