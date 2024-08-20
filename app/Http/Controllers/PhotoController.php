<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Str;


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
            $imageUrl =  ($photoModel->leadImage()?->file_path) ?  '/'.$photoModel->leadImage()?->file_path : url('/assets/img/placeholder.jpg'); // Fallback to default image if not found

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
                                <a class="dropdown-item" href="' . route('admin.photos.edit', ['photo' => $record->id]) . '">
                                    <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                </a>
                                <a class="dropdown-item" href="' . route('admin.photos.destroy', ['photo' => $record->id]) . '" data-method="DELETE" data-confirm="Are you sure you want to delete this photo?">
                                    <i class="fas fa-trash-alt m-r-5"></i> Delete
                                </a>
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        try {

            // Delete the file from storage
            if ($photo->path) {
                Storage::disk('public')->delete($photo->path);
            }
            
            $photo->delete();

            Toastr::success('Photo deleted successfully :)','Success');
            return redirect()->back();

        } catch (\Throwable $th) {
            
            Toastr::error('Photo delete fail :)','Error');
            return redirect()->back();
        }
    }

    public function individualPhoto(Photo $photo)
    {
        //$photo = Photo::with(['lead_image', 'regular_images'])->findOrFail($photo);
        $regular_images = $photo->regularImages(); 
        $lead_image = $photo->leadImage()?->file_path;

        // Fetch 4 recommended photos from the same event
        $recommendedPhotos = Photo::where('event_id', $photo->event_id)
                                ->where('id', '!=', $photo->id)
                                ->with('lead_image')
                                ->take(4)
                                ->get();

        // Check if guest token exists in session, if not, generate one
        if (!session()->has('guest_token')) {
            session(['guest_token' => Str::uuid()]);
        }

        return view('photos.show', compact('photo', 'recommendedPhotos', 'lead_image', 'regular_images'));
    }

}
