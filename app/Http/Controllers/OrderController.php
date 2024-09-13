<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use DB;
use Brian2694\Toastr\Facades\Toastr;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entity = 'orders';
        $entityName = 'Order';
        $columns = ['id', 'order_number', 'customer_name', 'total_amount', 'status', 'payment_method', 'created_at']; // Customize columns as needed

        return view('admin.templates.form-list-template', compact('entity', 'entityName', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function getOrderData(Request $request)
    {
        $draw            = $request->get('draw');
        $start           = $request->get("start");
        $rowPerPage      = $request->get("length"); // Total number of rows per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');

        $columnIndex     = $columnIndex_arr[0]['column']; // Column index
        $columnName      = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue     = $search_arr['value']; // Search value

        $orders = DB::table('orders')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.id')
            ->select('orders.*', 'users.name as customer_name', 'payment_methods.name as payment_method');

        $totalRecords = $orders->count();
        $totalRecordsWithFilter = $orders->where(function ($query) use ($searchValue) {
            $query->where('orders.order_number', 'like', '%' . $searchValue . '%')
                ->orWhere('users.name', 'like', '%' . $searchValue . '%')
                ->orWhere('orders.status', 'like', '%' . $searchValue . '%')
                ->orWhere('payment_methods.name', 'like', '%' . $searchValue . '%')
                ->orWhere('orders.total_amount', 'like', '%' . $searchValue . '%');
        })->count();

        $records = $orders->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('orders.order_number', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('orders.status', 'like', '%' . $searchValue . '%')
                    ->orWhere('payment_methods.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('orders.total_amount', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        $data_arr = [];
        foreach ($records as $key => $record) {
            $statusBadge = '<td><span class="badge badge-' . ($record->status === 'completed' ? 'success' : 'warning') . '">' . $record->status . '</span></td>';

            $action = '<td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v ellipse_color"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="' . route('admin.orders.show', ['order' => $record->id]) . '">
                                        <i class="fas fa-eye m-r-5"></i> View
                                    </a>
                                    <a class="dropdown-item" href="' . route('admin.orders.edit', ['order' => $record->id]) . '">
                                        <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                    </a>
                                    <form action="' . route('admin.orders.destroy', $record->id) . '" method="POST" style="display:inline;">
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
                "id"             => $record->id,
                "order_number"   => $record->order_number,
                "customer_name"  => $record->customer_name,
                "total_amount"   => $record->total_amount,
                "status"         => $statusBadge,
                "payment_method" => $record->payment_method,
                "created_at"     => $record->created_at,
                "action"         => $action,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try{
            // Delete the event itself
            $order->delete(); 
        
            Toastr::success('Order deleted successfully :)', 'Success');
            return redirect()->back();

        } catch (\Throwable $e) {
            Toastr::error('Order deletion failed: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
}
