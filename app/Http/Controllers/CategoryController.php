<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$categories = Category::all();
        $entity = 'categories';
        $entityName = 'Category';
        $columns = ['id', 'name', 'description', 'slug']; // Customize columns as needed

        return view('admin.templates.form-list-template', compact('entity', 'entityName', 'columns'));
        //return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    public function getCategoryData(Request $request)
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

        $categoriesQuery = DB::table('categories')
            //->leftJoin('category_event', 'categories.id', '=', 'category_event.category_id')
            //->leftJoin('events', 'category_event.event_id', '=', 'events.id')
            ->select(
                'categories.id', 
                'categories.name', 
                'categories.description', 
                'categories.slug',
                //DB::raw('GROUP_CONCAT(events.name SEPARATOR ", ") as event_names')
            );
            //->groupBy('categories.id', 'categories.name', 'categories.description','');

        // Filter records based on the search query
        $categoriesQuery->where(function ($query) use ($searchValue) {
            $query->where('categories.name', 'like', '%' . $searchValue . '%')
                ->orWhere('categories.id', 'like', '%' . $searchValue . '%');
                //->orWhere('events.name', 'like', '%' . $searchValue . '%');
        });

        $totalRecords = $categoriesQuery->count();

        $totalRecordsWithFilter = $categoriesQuery->count();

        $records = $categoriesQuery->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        $data_arr = [];
        foreach ($records as $key => $record) {
            $action = '<td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v ellipse_color"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="' . route('admin.categories.edit', ['category' => $record->id]) . '">
                                        <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                    </a>
                                    <a class="dropdown-item" href="' . route('admin.categories.destroy', ['category' => $record->id]) . '" data-method="DELETE" data-confirm="Are you sure you want to delete this category?">
                                        <i class="fas fa-trash-alt m-r-5"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </td>';

            $data_arr[] = [
                "id"          => $record->id,
                "name"        => $record->name,
                "description" => $record->description,
                "slug"        => $record->slug,
                "action"      => $action,
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
        try{
            $data = $request->validate([
                'name' => 'required|string',
            ]);

            $photo = Category::create($request->all());

            Toastr::success('Category created successfully.','Success');

            return redirect()->back();

        }catch(\Exception $e){
            Toastr::error('Category created failed.'.$e->getMessage(),'Error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'slug' => 'nullable|string',
            ]);
    
            // Update category details
            $category->update($data);
    
            Toastr::success('Updated Category successfully :)', 'Success');
            return redirect()->back();
    
        } catch (\Exception $e) {
            Toastr::error('Update Category failed: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
