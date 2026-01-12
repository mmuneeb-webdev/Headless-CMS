<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'users' => User::with('roles.permissions')->paginate(15)
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'roles'    => 'required|array',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // 🔑 API guard roles ONLY
        $roles = Role::whereIn('name', $data['roles'])
            ->where('guard_name', 'api')
            ->get();

        $user->syncRoles($roles);

        return response()->json([
            'message' => 'User created successfully',
            'user'    => $user->load('roles.permissions'),
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'  => 'sometimes|string',
            'roles' => 'sometimes|array',
        ]);

        $user->update($request->only('name'));

        if (isset($data['roles'])) {
            $roles = Role::whereIn('name', $data['roles'])
                ->where('guard_name', 'api')
                ->get();

            $user->syncRoles($roles);
        }

        return response()->json([
            'message' => 'User updated',
            'user'    => $user->load('roles.permissions'),
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted'
        ]);
    }
}
