<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json([
            'permissions' => Permission::where('guard_name', 'api')
                ->orderBy('name')
                ->get()
        ]);
    }

    public function show($id)
    {
        $permission = Permission::where('id', $id)
            ->where('guard_name', 'api')
            ->firstOrFail();

        return response()->json([
            'permission' => $permission,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z\-]+$/',
                'unique:permissions,name'
            ],
        ]);

        $permission = Permission::create([
            'name'       => $data['name'],
            'guard_name' => 'api',
        ]);

        return response()->json([
            'message'    => 'Permission created successfully',
            'permission' => $permission,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::where('id', $id)
            ->where('guard_name', 'api')
            ->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|min:3|max:50|regex:/^[a-z\-]+$/|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => $data['name'],
        ]);

        return response()->json([
            'message' => 'Permission updated successfully',
            'permission' => $permission,
        ]);
    }
    public function destroy(Permission $permission)
    {
        if ($permission->guard_name !== 'api') {
            abort(403, 'Cannot delete web permissions via API');
        }

        $permission->delete();

        return response()->json([
            'message' => 'Permission deleted',
        ]);
    }
}
