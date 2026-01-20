<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-users')->only(['index', 'show']);
        $this->middleware('permission:create-users')->only(['create', 'store']);
        $this->middleware('permission:edit-users')->only(['edit', 'update', 'updateRole']);
        $this->middleware('permission:delete-users')->only(['destroy']);
    }

    /**
     * List all users
     */
   public function index()
{
    return view('admin.users', [ // match the file name
        'users' => User::with('roles')->paginate(15),
        'roles' => Role::where('guard_name', 'web')->get(),
    ]);
}

    /**
     * Show form to create a new user
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'web')
            ->whereNotIn('name', ['super-admin'])
            ->get();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign roles (skip super-admin)
        $rolesToAssign = array_filter($request->roles ?? [], fn($r) => $r !== 'super-admin');
        if (!empty($rolesToAssign)) {
            $user->syncRoles($rolesToAssign);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Show form to edit a user
     */
    public function edit(User $user)
    {
        // Prevent editing super-admin email/name unless you want
        $roles = Role::where('guard_name', 'web')
            ->whereNotIn('name', ['super-admin'])
            ->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update user details (name, email, password)
     */
    public function update(Request $request, User $user)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        'password' => ['nullable', 'confirmed', Password::min(8)],
        'roles' => ['nullable', 'array'],
        'roles.*' => ['string', 'exists:roles,name'],
    ]);

    // Update user info
    $data = $request->only('name', 'email');

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    // -------------------------
    // NEW: Update roles here
    // -------------------------

    if ($request->has('roles')) {

        // Prevent removing last super-admin
        if ($user->hasRole('super-admin')) {
            $superAdminCount = User::role('super-admin')->count();

            if (
                $superAdminCount <= 1 &&
                !in_array('super-admin', $request->roles ?? [])
            ) {
                return back()->with('error', 'Cannot remove the last super-admin!');
            }
        }

        // Prevent assigning super-admin via UI
        $rolesToAssign = array_filter(
            $request->roles ?? [],
            fn ($role) => $role !== 'super-admin'
        );

        $user->syncRoles($rolesToAssign);
    }

    return redirect()->route('admin.users.index')
        ->with('success', 'User updated successfully!');
}

    /**
     * Update roles for a user
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name']
        ]);

        // Prevent removing last super-admin
        if ($user->hasRole('super-admin')) {
            $superAdminCount = User::role('super-admin')->count();
            if ($superAdminCount <= 1 && !in_array('super-admin', $request->roles ?? [])) {
                return back()->with('error', 'Cannot remove the last super-admin!');
            }
        }

        // Sync roles (skip super-admin)
        $rolesToAssign = array_filter($request->roles ?? [], fn($r) => $r !== 'super-admin');
        $user->syncRoles($rolesToAssign);

        return back()->with('success', 'User roles updated successfully.');
    }

    /**
     * Delete a user
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('super-admin')) {
            $superAdminCount = User::role('super-admin')->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Cannot delete the last super-admin!');
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
