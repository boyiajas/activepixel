<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // home page
    public function index()
    {
        // Fetch all roles
        $roles = Role::all();

        // Get the first role to set as active
        $firstRole = $roles->first();
        $userPermissions = $firstRole->permissions();
        // Fetch the permissions for the first role
        $permissions = $firstRole ? $firstRole->permissions : collect();

        // Extract unique resource names from the permissions
        $resourceNames = $permissions->pluck('name')
            ->map(function ($permissionName) {
                return explode(' ', $permissionName)[1]; // Get the resource name
            })
            ->unique(); // Get only unique resource names

        // Pass the first role, all roles, and the resource names to the view
        return view('settings.roles.index', compact('roles', 'firstRole', 'permissions', 'resourceNames', 'userPermissions'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('settings.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $role = Role::create($request->all());
        Toastr::success('Role created successfully :)','Success');
        
         // Redirect to the index route
        return redirect()->route('roles.index');

    }

    public function update(Request $request, Role $role)
    {
        //dd($request->all());
        // Collect all the permissions from the request
        /* $permissions = collect($request->input('permissions', []))
            ->flatMap(function ($actions, $resourceName) {
                return collect($actions)->map(function ($action, $key) use ($resourceName) {
                    return $key === 'module' ? $action : $action . ' ' . $resourceName;
                });
            }); */
         // Collect all the permission values into a flat array
        $permissions = collect($request->input('permissions', []))
        ->flatMap(function ($actions) {
            return array_values($actions);
        })
        ->toArray(); // Convert the collection to an array
        //dd($permissions);
        // Sync the permissions for the role
        $role->syncPermissions($permissions);

        Toastr::success('Permissions updated successfully :)', 'Success');
        return redirect()->route('roles.show', $role->id);
    }

    public function show(Role $role)
    {
        $firstRole = $role;
        // Fetch all roles
        $roles = Role::all();

        $permissions = Permission::all();
        $userPermissions = $role->permissions();

        $resourceNames = $permissions->pluck('name')
        ->map(function ($permissionName) {
            return explode(' ', $permissionName)[1];
        })->unique();
        
        return view('settings.roles.index', compact('roles','firstRole','permissions', 'resourceNames', 'userPermissions'));
    }

    public function destroy(Role $role)
    {
        /* return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully'); */
        try {
            $role->delete();
        
            Toastr::success('Role deleted successfully :)','Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Role deleted unsuccessfully :)','Error');
            return redirect()->back();
        }
    }
}
