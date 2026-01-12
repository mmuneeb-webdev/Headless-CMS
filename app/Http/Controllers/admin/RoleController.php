<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-roles')->only(['index', 'show']);
        $this->middleware('permission:create-roles')->only(['create', 'store']);
        $this->middleware('permission:edit-roles')->only(['edit', 'update']);
        $this->middleware('permission:delete-roles')->only(['destroy']);
    }

    public function index()
    {
        $roles = Role::where('guard_name', 'web')->with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::where('guard_name', 'web')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::where('guard_name', 'web')->get();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        // Prevent deletion of super-admin role
        if ($role->name === 'super-admin') {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Cannot delete super-admin role!');
        }

        $role->delete();
        
        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}