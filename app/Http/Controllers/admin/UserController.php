<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-users')->only(['index']);
        $this->middleware('permission:edit-users')->only(['updateRole']);
    }

    /**
     * Show users with role assignment UI
     */
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('roles')->paginate(15),
            'roles' => Role::where('guard_name', 'web')->get(),
        ]);
    }

    /**
     * Update roles for a user
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'roles' => ['nullable', 'array'],
        ]);

        // Prevent removing super-admin from the last super-admin
        if ($user->hasRole('super-admin')) {
            $superAdminCount = User::role('super-admin')->count();
            if ($superAdminCount <= 1 && !in_array('super-admin', $request->roles ?? [])) {
                return back()->with('error', 'Cannot remove the last super-admin!');
            }
        }

        $user->syncRoles($request->roles ?? []);

        return back()->with('success', 'User roles updated successfully.');
    }
}