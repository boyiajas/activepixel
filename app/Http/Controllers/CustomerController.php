<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\DigitalDownload;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('customer.dashboard');
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

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function settings()
    {
        return view('customer.partials.account-settings');
    }

    public function accountUpdate(Request $request)
    {
         // Validate the request data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Update user's name
        $user->name = $request->name;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the changes
        $user->save();

        Toastr::success('Account settings updated successfully.', 'Success');
         // Redirect back with success message
        return redirect()->back()->with('status', 'Account settings updated successfully.');
    }

    public function orderHistory()
    {
        $entityName = 'order-history';
        $columns = ['id', 'order_number', 'total_amount', 'status', 'created_at'];
        return view('customer.templates.entity-list', compact('entityName', 'columns'));
    }

    public function getOrderHistoryData()
    {
        $userId = Auth::id(); // Get the ID of the currently authenticated user

        $orders = Order::where('user_id', $userId) // Filter orders to only include those belonging to the current user
            ->select(['id', 'order_number', 'total_amount', 'status', 'created_at']);

        return DataTables::of($orders)
            ->addColumn('action', function ($order) {
                return '<a href="'.route('customer.orders.show', $order->id).'" class="btn btn-sm btn-info">View</a>';
            })
            ->make(true);
    }

    public function invoices()
    {
        $entityName = 'invoices';
        $columns = ['invoice_number', 'date', 'amount', 'status'];
        return view('customer.templates.entity-list', compact('entityName', 'columns'));
    }

    public function getInvoicesData()
    {
        $invoices = Invoice::select(['id', 'invoice_number', 'date', 'amount', 'status']);

        return DataTables::of($invoices)
            ->addColumn('action', function ($invoice) {
                return '<a href="'.route('invoice.show', $invoice->id).'" class="btn btn-sm btn-info">View</a>';
            })
            ->make(true);
    }

    public function digitalDownloads()
    {
        $entityName = 'digital-downloads';
        $columns = ['id', 'order_id', 'photo_id', 'download_image'];
        return view('customer.templates.entity-list', compact('entityName', 'columns'));
    }

    public function getDigitalDownloadsData()
    {
        $userId = Auth::id(); // Get the ID of the currently authenticated user

        $downloads = DigitalDownload::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId); // Filter downloads by orders belonging to the current user
        })->select(['id', 'order_id', 'photo_id', 'download_link']);

        return DataTables::of($downloads)
            ->addColumn('download_image', function ($download) {
                // Assuming download_link contains the URL to the image
                return '<img src="'.$download->download_link.'" alt="Image" style="width: 100px; height: auto;">';
            })
            ->addColumn('action', function ($download) {
                // Update the button to download the image
                return '<a href="'.$download->download_link.'" download class="btn btn-sm btn-info">Download</a>';
            })
            ->rawColumns(['download_image', 'action']) // Allow HTML rendering in columns
            ->make(true);
        }
}
