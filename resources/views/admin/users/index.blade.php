@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Users</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full bg-white shadow rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Roles</th>
                <th class="p-3 text-right">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-t">
                    <td class="p-3">{{ $user->name }}</td>
                    <td class="p-3">{{ $user->email }}</td>

                    <td class="p-3">
                        <form method="POST"
                              action="{{ route('admin.users.roles.update', $user) }}"
                              class="flex flex-wrap gap-2">
                            @csrf

                            @foreach ($roles as $role)
                                <label class="flex items-center gap-1 text-sm">
                                    <input type="checkbox"
                                           name="roles[]"
                                           value="{{ $role->name }}"
                                           @checked($user->hasRole($role->name))>
                                    {{ $role->name }}
                                </label>
                            @endforeach
                    </td>

                    <td class="p-3 text-right">
                            <button class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                                Save
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
