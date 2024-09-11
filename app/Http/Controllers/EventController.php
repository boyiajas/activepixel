<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Photo;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Illuminate\Support\Arr;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$events = Event::all();
        //return view('admin.events.index', compact('events'));
        $entity = 'events';
        $entityName = 'Event';
        $columns = ['id', 'name', 'description', 'slug','category_names']; // Customize columns as needed

        return view('admin.templates.form-list-template', compact('entity', 'entityName', 'columns'));
    }

    public function allEvents(Request $request)
    {
        $query = Event::query();

        // Apply filters based on the request
        if ($request->has('year')) {
            $query->whereYear('start_date', $request->input('year'));
        }
        if ($request->has('month')) {
            $query->whereMonth('start_date', $request->input('month'));
        }
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        $events = $query->paginate(12); // Paginate results, adjust the number as needed
        return view('events.index', compact('events'));
    }

    /* public function showEventPhotos(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $photos = Photo::where('event_id', $event->id)->images()->paginate(24);

        return view('events.photos', compact('event', 'photos'));
    } */
    /* public function showEventPhotos(Event $event, Request $request)
    {
        $event = Event::findOrFail($event->id);
        
        // Check if this is an AJAX request
        if ($request->ajax()) {
            $photos = Photo::where('event_id', $event->id)->paginate(6);
            return response()->json([
                'photos' => $photos->items(),
                'current_page' => $photos->currentPage(),
                'last_page' => $photos->lastPage(),
            ]);
        }
    
        $photos = Photo::where('event_id', $event->id)->paginate(6); // Load 6 photos initially
        return view('events.photos', compact('event', 'photos'));
    } */

    public function importPhotosSpreadSheet(Request $request, $eventId)
    {
        try{
            $request->validate([
                'photo_spreadsheet' => 'required|mimes:csv,xlsx|max:2048',
            ]);

            // Load the uploaded file
            $file = $request->file('photo_spreadsheet');//dd($file);

            // Read the file contents and process the data
            $data = Excel::toArray([], $file)[0];  // Assumes first sheet
            //dd($data, $eventId);
             // Skip the header row (row 0)
            foreach ($data as $index => $row) {
                if ($index == 0) {
                    continue; // Skip header row
                }

                if (!empty($row[0])) {  // Assuming race_number is in column 0
                    // Find the Photo by race number
                    $photo = Photo::where('event_id', $eventId)
                        ->where('race_number', $row[0])
                        ->first();

                    // If photo is found, update the name and description
                    if ($photo) {
                        $photo->name = $row[1] ?? $photo->name; // Assuming name is in column 1
                        $photo->description = $row[2] ?? $photo->description; // Assuming description is in column 2
                        $photo->save();
                    }
                }
            }

            Toastr::success('Photo information updated successfully.', 'Success');
            return redirect()->back();

        }catch(\Exception $e){
            Toastr::error('Photos information updated failed.'.$e->getMessage(),'Error');
            return redirect()->back();
        }
    }

    public function showEventPhotos(Event $event, Request $request)
    {
        //dd(request()->all());
        $event = Event::findOrFail($event->id);
        
        // Handle AJAX request
        if ($request->page) {
            $photos = Photo::where('event_id', $event->id)->paginate(6);

            
            return response()->json([
                'photos' => $photos->map(function ($photo) {

                    // Find the position of the last dot (.) before the extension
                    $lastDotPosition = strrpos($photo->leadImage()?->file_path, '.');

                    // Extract the base name and the extension
                    $baseName = substr($photo->leadImage()?->file_path, 0, $lastDotPosition);
                    $extension = substr($photo->leadImage()?->file_path, $lastDotPosition);

                    // Create the watermarked image path
                    $watermarked_image = $baseName . '.watermark' . $extension;

                    return [
                        'id' => $photo->id,
                        'name' => $photo->name,
                        'race_number' => $photo->race_number,
                        'price' => $photo->price,
                        'lead_image' => $watermarked_image,
                    ];
                }),
                'current_page' => $photos->currentPage(),
                'last_page' => $photos->lastPage(),
            ]);
        }

        // Initial page load
        $photos = Photo::where('event_id', $event->id)->paginate(6); // Load 6 photos initially
        return view('events.photos', compact('event', 'photos'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    public function getEventData(Request $request)
    {
        $draw            = $request->get('draw');
        $start           = $request->get('start');
        $rowPerPage      = $request->get('length'); // total number of rows per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');

        $columnIndex     = $columnIndex_arr[0]['column']; // Column index
        $columnName      = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue     = $search_arr['value']; // Search value

        // Base query with joins and selection
        $eventsQuery = DB::table('events')
        ->leftJoin('category_event', 'events.id', '=', 'category_event.event_id')
        ->leftJoin('categories', 'category_event.category_id', '=', 'categories.id')
        ->select(
            'events.id', 
            'events.name', 
            'events.description', 
            'events.slug',
            DB::raw('GROUP_CONCAT(categories.name SEPARATOR ", ") as category_names')
        )
        ->groupBy('events.id', 'events.name', 'events.description', 'events.slug');


        // Filter records based on the search query
        if ($searchValue) {
            $eventsQuery->where(function ($query) use ($searchValue) {
                $query->where('events.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('events.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('categories.name', 'like', '%' . $searchValue . '%');
            });
        }

        // Get total records count before filtering
        $totalRecords = $eventsQuery->count();

        // Apply filtering and pagination
        $records = $eventsQuery->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        // Prepare data for response
        $data_arr = [];
        foreach ($records as $record) {

            $eventModel = Event::find($record->id);

            $name = '<td>
                        <h2 class="table-avatar">
                            <a href="#" class="avatar avatar-sm mr-2">
                                <img class="avatar-img rounded-circle" src="' . $eventModel->event_image_url . '" alt="Event Image">
                            </a>
                            <a href="' . route('admin.events.show', $record->id) . '">' . $record->name . '
                                <span>' . $record->id . '</span>
                            </a>
                        </h2>
                    </td>';

            $action = '<td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v ellipse_color"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="' . route('admin.events.show', $record->id) . '">
                                        <i class="fas fa-eye m-r-5"></i> View
                                    </a>
                                    <a class="dropdown-item" href="' . route('admin.events.edit', $record->id) . '">
                                        <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                    </a>
                                    <form action="' . route('admin.events.destroy', $record->id) . '" method="POST" style="display:inline;">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <button type="submit" class="dropdown-item" onclick="return confirm(\'Are you sure you want to delete this event?\')">
                                            <i class="fas fa-trash-alt m-r-5"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>';

            $data_arr[] = [
                "id"            => $record->id,
                "name"          => $name,
                "description"   => $record->description,
                "category_names" => $record->category_names,
                "slug"        => $record->slug,
                "action"        => $action,
            ];
        }

        // Prepare the response
        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
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
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'location' => 'nullable|string|max:255',
                'slug' => 'nullable|string',
                'description' => 'nullable|string',
                'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            ]);

             // Initialize event data
            $eventData = [
                'name' => $request->input('name'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'location' => $request->input('location'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description'),
                'event_image' => null
            ];

            // Handle file upload
            if ($request->hasFile('event_image')) {
                $photo = $request->file('event_image');
                $filename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $photo->getClientOriginalExtension();
                $directory = 'uploads/events/';
                $file_location = $directory . $filename . '.' . $extension;
            

                // Ensure directory exists
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                // Move the file to the desired location
                $photo->move($directory, $filename . '.' . $extension);

                // Update event data with image path
                $eventData['event_image'] = $file_location;

                // Resize the image
                $image = Image::make($file_location);
                $image->fit(800, 600)->save($file_location); // Adjust dimensions as needed
            
            }

            // Create the event
            $event = Event::create($eventData);

            Toastr::success('Event created successfully.', 'Success');
            return redirect()->back();

        }catch(\Exception $e){
            Toastr::error('Event created failed.'.$e->getMessage(),'Error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function individualEvents(Event $event)
    {

        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        try{
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'location' => 'nullable|string|max:255',
                'slug' => 'nullable|string',
                'description' => 'nullable|string',
                'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            ]);


            // Get the old image path
            $oldImagePath = $event->event_image;

            // Update event details excluding the image path
            $eventData = Arr::except($data, ['event_image']);
            $event->update($eventData);

             // Handle file upload and resizing
            if ($request->hasFile('event_image')) {
                $photo = $request->file('event_image');
                $directory = 'images/events/';
                $filename = 'event_' . $event->id . '_' . time();
                $extension = $photo->getClientOriginalExtension();
                $file_location = $directory . $filename . '.' . $extension;

                // Ensure directory exists
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                // Move the file
                $photo->move($directory, $filename . '.' . $extension);

                // Resize the image
                $image = Image::make($file_location);
                $image->fit(800, 600)->save($file_location); // Adjust size as needed

                // Update the event with the new image path
                $event->update(['event_image' => $file_location]);

                // Remove the old image if it exists
                if ($oldImagePath && File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            Toastr::success('Updated Event successfully :)', 'Success');
            return redirect()->back();

        } catch (\Exception $e) {
            Toastr::error('Update Event failed: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }


    public function deleteSelected(Request $request)
    {
        try {
            $eventIds = $request->input('ids', []);

            // Fetch the selected events
            $events = Event::whereIn('id', $eventIds)->get();

            foreach ($events as $event) {
                // Delete associated photos and their uploads
                foreach ($event->photos as $photo) {
                    $uploads = $photo->upload()->get();

                    foreach ($uploads as $upload) {
                        $filePath = public_path($upload->file_path);

                        // Delete different image sizes
                        $sizes = ['_200_300','.watermark_200_300', '.watermark'];
                        foreach ($sizes as $size) {
                            $sizedFilePath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . pathinfo($filePath, PATHINFO_FILENAME) . $size . '.' . $upload->extension;
                            if (File::exists($sizedFilePath)) {
                                File::delete($sizedFilePath);
                            }
                        }

                        // Delete the original file
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }

                        // Delete the upload record from the database
                        $upload->delete();
                    }

                    // Delete the photo record
                    $photo->delete();
                }

                // Delete the event image if it exists
                if ($event->event_image && File::exists(public_path($event->event_image))) {
                    File::delete(public_path($event->event_image));
                }

                // Delete the event itself
                $event->delete();
            }

            return response()->json(['status' => 'success', 'message' => 'Selected events deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete selected events.']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        try {
            // Delete associated photos and their uploads
            foreach ($event->photos as $photo) {
                // Get the uploads associated with the photo
                $uploads = $photo->upload()->get();

                foreach ($uploads as $upload) {
                    // Get the file path
                    $filePath = public_path($upload->file_path);

                    // Delete different image sizes
                    $sizes = ['_200_300','.watermark_200_300', '.watermark'];
                    foreach ($sizes as $size) {
                        $sizedFilePath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . pathinfo($filePath, PATHINFO_FILENAME) . $size . '.' . $upload->extension;
                        if (File::exists($sizedFilePath)) {
                            File::delete($sizedFilePath);
                        }
                    }

                    // Delete the original file
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }

                    // Delete the upload record from the database
                    $upload->delete();
                }

                // Delete the photo record
                $photo->delete();
            }

            // Delete the event image if it exists
            if ($event->event_image && File::exists(public_path($event->event_image))) {
                File::delete(public_path($event->event_image));
            }

            // Delete the event itself
            $event->delete(); 

            return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Event deletion failed.');
        }
    }
}
