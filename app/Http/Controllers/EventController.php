<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Photo;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
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

    public function showEventPhotos(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $photos = Photo::where('event_id', $event->id)->images()->paginate(10);

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
