<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Upload;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use DB;
use ZipArchive;
use Illuminate\Support\Str;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Jobs\ProcessBulkPhotos;


class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$photos = Photo::all();
        //return view('admin.photos.index', compact('photos'));
        $entity = 'photos';
        $entityName = 'Photo';
        $columns = ['id', 'name', 'race_number', 'price', 'stock_status', 'downloadable', 'category_name','event_name']; // Customize columns as needed

        return view('admin.templates.form-list-template', compact('entity', 'entityName', 'columns'));
    }

    public function getImage($filename)
{
        $path = $filename; // Adjust the folder as needed

        if (Storage::exists($path)) {
            $file = Storage::get($path);
            $type = Storage::mimeType($path);

            return response($file, 200)->header('Content-Type', $type);
        } else {
            abort(404, 'Image not found.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $events = Event::all();
        $categories = Category::all();

        return view('admin.photos.create', compact('events', 'categories'));
    }

    public function getPhotosData(Request $request)
    {
        $draw            = $request->get('draw');
        $start           = $request->get("start");
        $rowPerPage      = $request->get("length"); // total number of rows per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');

        $columnIndex     = $columnIndex_arr[0]['column']; // Column index
        $columnName      = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue     = $search_arr['value']; // Search value

        $photos = DB::table('photos')
            ->leftJoin('categories', 'photos.category_id', '=', 'categories.id')
            ->leftJoin('events', 'photos.event_id', '=', 'events.id')
            ->select('photos.*', 'categories.name as category_name', 'events.name as event_name');

        $totalRecords = $photos->count();
        $totalRecordsWithFilter = $photos->where(function ($query) use ($searchValue) {
            $query->where('photos.name', 'like', '%' . $searchValue . '%')
                ->orWhere('photos.id', 'like', '%' . $searchValue . '%')
                ->orWhere('categories.name', 'like', '%' . $searchValue . '%')
                ->orWhere('events.name', 'like', '%' . $searchValue . '%')
                ->orWhere('photos.race_number', 'like', '%' . $searchValue . '%')
                ->orWhere('photos.price', 'like', '%' . $searchValue . '%')
                ->orWhere('photos.stock_status', 'like', '%' . $searchValue . '%')
                ->orWhere('photos.downloadable', 'like', '%' . $searchValue . '%');
        })->count();

        $records = $photos->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('photos.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('photos.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('categories.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('events.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('photos.race_number', 'like', '%' . $searchValue . '%')
                    ->orWhere('photos.price', 'like', '%' . $searchValue . '%')
                    ->orWhere('photos.stock_status', 'like', '%' . $searchValue . '%')
                    ->orWhere('photos.downloadable', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        $data_arr = [];
        foreach ($records as $key => $record) {

            // Assuming you have an Eloquent model `Photo` where `leadImage()` returns the image file path.
            $photoModel = Photo::find($record->id); 
            $imageUrl =  ($photoModel->leadImage()?->file_path) ?  url($photoModel->leadImage()?->file_path) : url('/assets/img/placeholder.jpg'); // Fallback to default image if not found

            $downloadable = $record->downloadable ? 'Yes' : 'No';
            $stock_status = $record->stock_status ? 'In Stock' : 'Out of Stock';

            $name = '<td>
                        <h2 class="table-avatar">
                            <a href="#" class="avatar avatar-sm mr-2">
                                <img class="avatar-img rounded-circle" src="' . $imageUrl . '" alt="Photo Image">
                            </a>
                            <a href="#">' . $record->name . '
                                <span>' . $record->id . '</span>
                            </a>
                        </h2>
                    </td>';
            $status = '<td>
                        <div class="actions">
                            <a href="#" class="btn btn-sm bg-success-light mr-2">' . $stock_status . '</a>
                        </div>
                    </td>';
            $action = '<td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#"class="action-icon dropdown-toggle" data-toggle="dropdown"aria-expanded="false">
                                <i class="fas fa-ellipsis-v ellipse_color"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="' . route('admin.photos.show', $record->id) . '">
                                    <i class="fas fa-eye m-r-5"></i> View
                                </a>
                                <a class="dropdown-item" href="' . route('admin.photos.edit', $record->id) . '">
                                    <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                </a>
                                <form action="' . route('admin.photos.destroy', ['photo' => $record->id]) . '" method="POST" style="display:inline;">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                    <button type="submit" class="dropdown-item" onclick="return confirm(\'Are you sure you want to delete this event?\')">
                                        <i class="fas fa-trash-alt m-r-5"></i> Delete
                                    </button>
                                </form>
                            </div>
                            
                        </div>
                    </td>';

            $data_arr [] = [
                "id"            => $record->id,
                "name"          => $name,
                "race_number"   => $record->race_number,
                "price"         => $record->price,
                "stock_status"  => $status,
                "downloadable"  => $downloadable,
                "category_name" => $record->category_name,
                "event_name"    => $record->event_name,
                "action"        => $action, 
            ];
        }

        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData"               => $data_arr
        ];

        return response()->json($response);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        try{
            $data = $request->validate([
                'name' => 'required|string',
                'race_number' => 'required|integer',
                'price' => 'required|numeric',
                'stock_status' => 'required|in:in_stock,out_of_stock',
                'downloadable' => 'boolean',
                //'update_date' => 'required|date',
                //'published_date' => 'required|date',
                //'photo_type' => 'required|string',
                //'event_id' => 'required|exists:events,id',
                //'category_id' => 'required|exists:categories,id'
            ]);

            $photo = Photo::create($request->all());

            Toastr::success('Photo created successfully.','Success');

            return redirect()->route('admin.photos.edit', compact('photo'));

        }catch(\Exception $e){
            Toastr::error('Photo created failed.'.$e->getMessage(),'Error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        return view('admin.photos.show', compact('photo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        $property = [];
        $regular_images = $photo->regularImages(); 
        $lead_image = $photo->leadImage()?->file_path;
        $events = Event::all();
        $categories = Category::all();
        return view('admin.photos.edit', compact('photo','lead_image', 'regular_images', 'events', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photo $photo)
    {
        //dd($request->all());
        try{
            $data = $request->validate([
                'name' => 'required|string',
                'race_number' => 'required|integer',
                'price' => 'required|numeric',
                'stock_status' => 'required|in:in_stock,out_of_stock',
                'downloadable' => 'boolean',
                'update_date' => now(),
                //'published_date' => 'required|date',
                //'photo_type' => 'required|string',
                //'event_id' => 'required|exists:events,id',
                //'category_id' => 'required|exists:categories,id',
            ]);


            //dd($data);
            $photo->update($request->all());

            Toastr::success('Updated Photo successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            Toastr::error('Update Photo failed :)','Error');
            return redirect()->back();
        }
    }

    // Search for photos
    public function search(Request $request)
    {
        $query = $request->input('query');
        $photos = Photo::where('name', 'like', "%{$query}%")
            ->orWhere('race_number', 'like', "%{$query}%")
            ->get();

        return view('admin.photos.index', compact('photos'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['error' => 'No IDs provided'], 400);
        }

        try {
            foreach ($ids as $id) {
                $photo = Photo::find($id);

                if (!$photo) {
                    continue; // Skip if photo not found
                }

                // Delete all associated uploaded files from storage
                $uploads = $photo->upload()->get();

                foreach ($uploads as $upload) {
                    // Get the full path to the original file
                    $originalFilePath = public_path($upload->file_path);
                    \Log::info("the upload path is {$upload->file_path}");

                    // Extract the directory and base filename without the size suffix and extension
                    $directory = pathinfo($originalFilePath, PATHINFO_DIRNAME);
                    $filename = pathinfo($originalFilePath, PATHINFO_FILENAME);  // Filename without extension
                    $extension = $upload->extension;

                    // Define the different image sizes used in the storeImage function
                    $sizes = ['_143_83', '_265_163', '_400_161', '_835_467', '_1920_600'];

                    // Delete the resized images
                    foreach ($sizes as $size) {
                        $sizedFilePath = $originalFilePath . $size . '.' . $extension;
                        if (File::exists($sizedFilePath)) {
                            \Log::info("here we are deleting the resize {$size} with located in {$sizedFilePath}");
                            File::delete($sizedFilePath);
                        }
                    }

                    // Delete the original image file
                    if (File::exists($originalFilePath)) {
                        \Log::info("here we are deleting the original file: {$originalFilePath}");
                        File::delete($originalFilePath);
                    }

                     // Check if the directory is empty and delete it if it is
                    if (is_dir($directory) && count(glob($directory . '/*')) === 0) {
                        \Log::info("Deleting empty directory: {$directory}");
                        rmdir($directory);
                    }

                    // Delete the record from the uploads table
                    $upload->delete();
                }

                // Delete the photo record
                $photo->delete();
            }

            Toastr::success('Selected photos deleted successfully :)', 'Success');

            return response()->json(['success' => 'Selected rows deleted successfully']);
        } catch (\Throwable $th) {
            Toastr::error('Failed to delete selected photos :)', 'Error');
            return response()->json(['error' => 'Failed to delete selected rows'], 500);
        }
    }

    public function destroy(Photo $photo)
    {
        try {
            
            // Delete all associated uploaded files from storage
            $uploads = $photo->upload()->get();

            foreach ($uploads as $upload) {
                // Get the full path to the original file
                $originalFilePath = public_path($upload->file_path);//dd($upload);

                // Extract the directory and base filename without the size suffix and extension
                $directory = pathinfo($originalFilePath, PATHINFO_DIRNAME);
                $filename = pathinfo($originalFilePath, PATHINFO_FILENAME);  // Filename without extension
                $extension = $upload->extension;//dd($directory, $filename, $extension, $originalFilePath);

                // Define the different image sizes used in the storeImage function
                $sizes = ['_143_83', '_265_163', '_400_161', '_835_467', '_1920_600'];

                // Delete the resized images
                foreach ($sizes as $size) {
                    $sizedFilePath = $originalFilePath . $size . '.' . $extension;
                    if (File::exists($sizedFilePath)) {
                        File::delete($sizedFilePath);
                    }
                }

                // Delete the original image file
                if (File::exists($originalFilePath)) {
                    File::delete($originalFilePath);
                }

                // Check if the directory is empty and delete it if it is
                if (is_dir($directory) && count(glob($directory . '/*')) === 0) {
                    \Log::info("Deleting empty directory: {$directory}");
                    rmdir($directory);
                }

                // Delete the record from the uploads table
                $upload->delete();
            }

            // Delete the photo record
            $photo->delete();

            Toastr::success('Photo deleted successfully :)', 'Success');

            return redirect()->back();

        } catch (\Throwable $th) {
            Toastr::error('Photo delete failed :)', 'Error');
            return redirect()->back();
        }
    }

    public function importBulkPhotos(Event $event_id)
    {
        $categories = Category::all();
        return view('admin.photos.import-bulk-photos', ['event'=> $event_id], compact('categories'));
    }

    public function checkChunk(Request $request, $event_id)
    {
        $chunkNumber = $request->input('resumableChunkNumber');
        $chunkSize = $request->input('resumableChunkSize');
        $identifier = $request->input('resumableIdentifier');
        $filename = $request->input('resumableFilename');

        // Remove the unique part from the identifier
        $identifierParts = explode('-', $identifier);

         // Create regex pattern to match chunk files
        //$chunkFileName = $filename . '-[^]+-' . $identifierParts[0].'-'.'[^-]+.'. $chunkNumber . '.part';
        // Create regex pattern to match chunk files
        $chunkFileName = $filename . '-\w+-' . $identifierParts[0] . '-\w+.' . $chunkNumber . '.part';
        // Create regex pattern to match chunk files
        // Create regex pattern to match chunk files
        $pattern = '/^' . preg_quote($filename, '/') . '-\w+-' . preg_quote($identifierParts[0], '/') . '-\w*' . '\.' . $chunkNumber . '\.part$/';

        // Generate the chunk file name
        //$chunkFileName = $filename . '-' . $identifier . '-' . $chunkNumber . '.part';
        $chunkFileName2 = $filename . '.part';
        $chunkPath = storage_path('app/chunks/' . $chunkFileName);

        // Get all files in the chunks directory
        $chunkDirectory = storage_path('app/chunks/');
        $files = File::files($chunkDirectory);

        // Check if any file matches the regex pattern
        foreach ($files as $file) {
            
            $fileName = pathinfo($file, PATHINFO_BASENAME); //return response()->json(['file'=> $fileName,'pattern' => $pattern], 200);
            if (preg_match($pattern, $fileName)) {
                return response()->json(['chunkExists' => true, 'file' => $fileName], 200);
            }
        }

        // Log the chunk check request for debugging purposes
       /*  \Log::info("Checking chunk {$chunkNumber} for identifier {$identifier} at path: {$chunkPath}");

        // Check if the chunk exists
        if (File::exists($chunkPath)) {
            return response()->json(['chunkExists' => true], 200);
        }
 */
        return response()->json(['chunkExists' => false, 'chunkFileName' => $chunkFileName, 'chunkFileName2' => $chunkFileName2, 'dump' => $request->all()], 404);
    }

    public function importBulkPhotosStore(Request $request, Event $event_id)
    {   //dd($request->hasFile('zip_file'));
        $receiver = new FileReceiver('zip_file', $request, HandlerFactory::classFromRequest($request));

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        // Log the chunk check request for debugging purposes
        
        $save = $receiver->receive();

        if ($save->isFinished()) {

            $file = $save->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name
    
            $disk = Storage::disk('local'); // save to local disk (you can change this to a specific disk)
            $path = $disk->putFileAs('uploads/photos/zipfile', $file, $fileName); // store the file */
    
    
            // File has been uploaded fully
            //$file = $save->getFile();
            //$zipFilePath = $file->getPathname();
            
            //dd($zipFilePath);
            // Ensure that the path is correct and file exists before processing
            $fullPath = storage_path('app/' . $path);

            if (File::exists($fullPath)) {
                // Delete the original file
                unlink($file->getPathname());
                // Assuming the photo_action is sent as part of the request
                $photoAction = $request->input('import_option'); // default to 'skip'
                $photoType = $request->input('photo_type');

                // Dispatch a job to process the photos in the background
                ProcessBulkPhotos::dispatch($fullPath, $event_id->id, $photoAction, $photoType);

                return response()->json(['message' => 'Upload successful and processing started'], 200);
            } else {
                return response()->json(['message' => 'File not found after upload'], 404);
            }
        }

        // If chunk is not complete, return progress
        $handler = $save->handler();
        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    public function individualPhoto(Photo $photo)
    {
        //dd($photo);
        //$photo = Photo::with(['lead_image', 'regular_images'])->findOrFail($photo);
        $regular_images = $photo->regularImages(); 
        $lead_image = $photo->leadImage()?->file_path;

        // Fetch 4 recommended photos from the same event
        $recommendedPhotos = Photo::where('event_id', $photo->event_id)
                                ->where('id', '!=', $photo->id)
                                //->with('lead_image')
                                ->take(4)
                                ->get(); 

        // Check if guest token exists in session, if not, generate one
        if (!session()->has('guest_token')) {
            session(['guest_token' => Str::uuid()]);
        }

        return view('photos.show', compact('photo', 'recommendedPhotos', 'lead_image', 'regular_images'));
    }

}
