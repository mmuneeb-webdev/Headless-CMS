<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json([
            'roles' => Role::where('guard_name', 'api')
                ->with('permissions')
                ->get()
        ]);
    }

    public function show($id)
    {
        $role = Role::where('id', $id)
            ->where('guard_name', 'api')
            ->with('permissions')
            ->firstOrFail();

        return response()->json([
            'role' => $role,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name'       => $data['name'],
            'guard_name' => 'api',
        ]);

        if (!empty($data['permissions'])) {
            $permissions = Permission::whereIn('name', $data['permissions'])
                ->where('guard_name', 'api')
                ->get();

            $role->syncPermissions($permissions);
        }

        return response()->json([
            'message' => 'Role created successfully',
            'role'    => $role->load('permissions'),
        ], 201);
    }

    public function update(Request $request, Role $role)
    {
        // 🔒 Prevent editing WEB roles from API
        if ($role->guard_name !== 'api') {
            abort(403, 'Cannot modify web roles via API');
        }

        $data = $request->validate([
            'name'        => 'sometimes|string|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        if (isset($data['name'])) {
            $role->update(['name' => $data['name']]);
        }

        if (array_key_exists('permissions', $data)) {
            $permissions = Permission::whereIn('name', $data['permissions'] ?? [])
                ->where('guard_name', 'api')
                ->get();

            $role->syncPermissions($permissions);
        }

        return response()->json([
            'message' => 'Role updated successfully',
            'role'    => $role->load('permissions'),
        ]);
    }

    public function destroy(Role $role)
    {
        if ($role->guard_name !== 'api') {
            abort(403, 'Cannot delete web roles via API');
        }

        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully',
        ]);
    }
}
