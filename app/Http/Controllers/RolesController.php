<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch roles where guard_name is 'web'
        $roles = Role::where('guard_name', 'web')->get();
        $title = "Permission";
    
        return view('settings.permission_settings', [
            'roles' => $roles,
            'title' => $title // Add the title to the data array
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $equb = Permission::where('name', 'like', '%equb%')->get()->sortBy('name');
        $title = "Permission";
        
        return view('roles.create_role', [ 'title' => $title,'equb' => $equb,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   /* public function store(Request $request)
    {
        // Validate the incoming request
        $formFields = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => 'array', // Ensure permissions is an array
        ]);
    
        // Set the guard name
        $formFields['guard_name'] = 'web'; // Adjust if necessary
    
        try {
            // Create the role
            $role = Role::create($formFields);
    
            // Fetch permissions by IDs
            $filteredPermissions = Permission::whereIn('id', $request->input('permissions'))->get();
    
            // Log filtered permissions for debugging
            \Log::info('Filtered Permissions: ', $filteredPermissions->pluck('name')->toArray());
    
            // Sync permissions with the role
            $role->syncPermissions($filteredPermissions);
    
            // Clear the cache
            Artisan::call('cache:clear');
    
            // Flash success message
            Session::flash('success', 'Role created successfully.');
            return response()->json(['error' => false, 'message' => 'Role created successfully.', 'role' => $role]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating role: ' . $e->getMessage());
    
            // Flash error message
            Session::flash('error', 'Failed to create role. ' . $e->getMessage());
            return response()->json(['error' => true, 'message' => 'Failed to create role.']);
        }
    }
    */
    public function store(Request $request)
    {
        // Validate the incoming request
        $formFields = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => 'array', // Ensure permissions is an array
        ]);
    
        // Set the guard name
        $formFields['guard_name'] = 'web'; // Adjust if necessary
    
        try {
            // Create the role
            $role = Role::create($formFields);
    
            // Fetch permissions by IDs
            $filteredPermissions = Permission::whereIn('id', $request->input('permissions'))->get();
    
            // Log filtered permissions for debugging
            \Log::info('Filtered Permissions: ', $filteredPermissions->pluck('name')->toArray());
    
            // Sync permissions with the role
            $role->syncPermissions($filteredPermissions);
    
            // Clear the cache
            Artisan::call('cache:clear');
    
            // Flash success message
            Session::flash('success', 'Role created successfully.');
    
            // Redirect back to the settings/permissions page
            return redirect()->route('permissions.index'); // Adjust the route name as necessary
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating role: ' . $e->getMessage());
    
            // Flash error message
            Session::flash('error', 'Failed to create role. ' . $e->getMessage());
    
            // Redirect back to the form with input
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Permission";
        try {
            $role = Role::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Role not found.');
        }
    
        $role_permissions = $role->permissions;
        $guard = $role->guard_name == 'api' ? 'api' : 'web';
        return view('roles.edit_role', ['title' => $title, 'role' => $role, 'role_permissions' => $role_permissions, 'guard' => $guard, 'user' => getAuthenticatedUser()]);
    }
    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $formFields = $request->validate([
            'name' => ['required']
        ]);
    
        // Find the role by ID
        $role = Role::findOrFail($id);
        $role->name = $formFields['name'];
        $role->save();
    
        // Get permissions from the request, defaulting to an empty array if null
        $permissions = $request->input('permissions', []);
    
        // Filter and sync permissions
        $filteredPermissions = array_filter($permissions, function ($permission) {
            return $permission != 0;
        });
    
        $role->permissions()->sync($filteredPermissions);
    
        // Clear cache
        Artisan::call('cache:clear');
    
        // Flash message and redirect
        Session::flash('message', 'Role updated successfully.');
        return redirect($request->input('redirect_url')); // Assumes you have a redirect URL in the form
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

   /* public function destroy($id)
    {
        $title = "Permission";
        $response = DeletionService::delete(Role::class, $id, 'Role');
        return $response;
    }*/
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
    
            // Flash success message
            Session::flash('success', 'Role deleted successfully.');
    
            return redirect()->route('settings.permissions'); // Redirect to the permissions page
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error deleting role: ' . $e->getMessage());
    
            // Flash error message
            Session::flash('error', 'Failed to delete role. ' . $e->getMessage());
    
            return redirect()->back();
        }
    }
    public function create_permission()
    {
        Permission::create(['name' => 'edit_equb', 'guard_name' => 'web']);
    }

    public function rolesPermision()
    {
        $rolesAndPermissions = $this->getRolesAndPermissions();
        return response()->json($rolesAndPermissions);
    }
    public function getRolesAndPermissions()
    {
        return Role::with('permissions')->get()->map(function ($role) {
            return [
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ];
        });
    }
}
