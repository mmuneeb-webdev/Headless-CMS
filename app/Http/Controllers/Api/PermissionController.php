<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // Read-only API
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

    // 🚫 Block mutations via API
    public function store(Request $request)
    {
        abort(403, 'Creating permissions is only allowed via the Web admin dashboard');
    }

    public function update(Request $request, $id)
    {
        abort(403, 'Editing permissions is only allowed via the Web admin dashboard');
    }

    public function destroy(Permission $permission)
    {
        abort(403, 'Deleting permissions is only allowed via the Web admin dashboard');
    }
}
