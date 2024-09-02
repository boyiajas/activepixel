<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function __construct()
    {
        //$this->middleware('role:admin');
    }
    
    /** user list */
    public function userList()
    {
        $entity = 'users';
        $entityName = 'Users';
        $columns = ['id', 'name', 'email', 'last_login', 'phone_number', 'role_name', 'status']; // Customize columns as needed

        return view('admin.templates.form-list-template', compact('entity', 'entityName', 'columns'));
        //return view('usermanagement.listuser');
    }

     /** edit record */
     public function userView($user_id)
     {
         $userData = User::where('id',$user_id)->first();
         $userRoles = $userData->roles;
         $roles = Role::all();
         return view('usermanagement.useredit',compact('userData','userRoles','roles'));
     }

    /** add neew users */
    public function userAddNew()
    {
        $roles = Role::all();
        return view('usermanagement.useraddnew', compact('roles'));
    }

     /** update record */
     public function userUpdate(Request $request)
     {
         DB::beginTransaction();
         try {
             $updateRecord = [
                 'name'         => $request->name,
                 'email'        => $request->email,
                 'phone_number' => $request->phone_number,
                 'position' => $request->position,
                 // Removed 'position' since roles will be used instead
             ];
     
             // Update user record
             $user = User::where('id', $request->user_id)->firstOrFail();
             $user->update($updateRecord);
     
             // Sync user roles
             $user->syncRoles($request->role_name ? $request->role_name : 'user');
     
             DB::commit();
             Toastr::success('Updated record successfully :)','Success');
             return redirect()->back();
         } catch (\Exception $e) {
             DB::rollback();
             Toastr::error('Update record failed :)','Error');
             return redirect()->back();
         }
     }
     

    /** get users data */
    public function getUsersData(Request $request)
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
    
        $users = DB::table('users')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name');
    
        $totalRecords = $users->count();
    
        $totalRecordsWithFilter = $users->where(function ($query) use ($searchValue) {
            $query->where('users.name', 'like', '%' . $searchValue . '%')
                ->orWhere('users.id', 'like', '%' . $searchValue . '%')
                ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                ->orWhere('roles.name', 'like', '%' . $searchValue . '%')
                ->orWhere('users.phone_number', 'like', '%' . $searchValue . '%')
                ->orWhere('users.status', 'like', '%' . $searchValue . '%');
        })->count();
    
        $records = $users->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('users.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.email', 'like', '%' . $searchValue . '%')
                    ->orWhere('roles.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.phone_number', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.status', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowPerPage)
            ->get();
    
        $data_arr = [];
        foreach ($records as $key => $record) {
            $name = '<td>
                        <h2 class="table-avatar">
                            <a href="profile.html" class="avatar avatar-sm mr-2">
                                <img class="avatar-img rounded-circle" src="' . url('/assets/img/hotel_logo.png') . '" alt="User Image">
                            </a>
                            <a href="#">' . $record->name . '
                                <span>' . $record->id . '</span>
                            </a>
                        </h2>
                    </td>';
            $status = '<td>
                        <div class="actions">
                            <a href="#" class="btn btn-sm bg-success-light mr-2">' . $record->status . '</a>
                        </div>
                    </td>';
            $action = '<td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#"class="action-icon dropdown-toggle" data-toggle="dropdown"aria-expanded="false">
                                <i class="fas fa-ellipsis-v ellipse_color"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="' . route('admin.users.edit', ['user_id' => $record->id]) . '">
                                    <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                </a>
                                <a class="dropdown-item" href="' . route('admin.users.delete', ['user_id' => $record->id]) . '">
                                    <i class="fas fa-trash-alt m-r-5"></i> Delete
                                </a>
                            </div>
                        </div>
                    </td>';
    
            $data_arr [] = [
                "id"        => $record->id,
                "name"         => $name,
                "email"        => $record->email,
                "role_name"    => $record->role_name, // Return role instead of position
                "last_login"   => Carbon::parse($record->last_login)->diffForHumans(),
                "phone_number" => $record->phone_number,
                "status"       => $status, 
                "action"       => $action, 
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
    
     /** delete record */
     public function userDelete($user_id)
     {
         try {
 
             $deleteRecord = User::find($user_id);
             $deleteRecord->delete();
             Toastr::success('User deleted successfully :)','Success');
             return redirect()->back();
         
         } catch(\Exception $e) {
             DB::rollback();
             Toastr::error('User delete fail :)','Error');
             return redirect()->back();
         }
     }

     public function deleteSelected(Request $request)
    {
        try {
            $userIds = $request->input('ids', []);

            // Fetch the selected users
            $users = User::whereIn('id', $userIds)->get();

            foreach ($users as $user) {
                // Delete the user record
                $user->delete();
            }

            return response()->json(['status' => 'success', 'message' => 'Selected users deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete selected users.']);
        }
    }
}
