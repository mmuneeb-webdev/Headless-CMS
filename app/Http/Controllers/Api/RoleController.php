<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // Read-only API
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

    // 🚫 Block mutations via API
    public function store(Request $request)
    {
        abort(403, 'Creating roles is only allowed via the Web admin dashboard');
    }

    public function update(Request $request, Role $role)
    {
        abort(403, 'Editing roles is only allowed via the Web admin dashboard');
    }

    public function destroy(Role $role)
    {
        abort(403, 'Deleting roles is only allowed via the Web admin dashboard');
    }
}
