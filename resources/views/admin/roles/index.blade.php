@extends('admin.layout')

@section('title', 'Roles')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Roles</h2>
    <a href="{{ route('admin.roles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Role</a>
</div>

<table class="min-w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="py-2 px-4">ID</th>
            <th class="py-2 px-4">Role Name</th>
            <th class="py-2 px-4">Permissions</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
        <tr class="border-b">
            <td class="py-2 px-4">{{ $role->id }}</td>
            <td class="py-2 px-4">{{ $role->name }}</td>
            <td class="py-2 px-4">
                @foreach($role->permissions as $perm)
                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">{{ $perm->name }}</span>
                @endforeach
            </td>
            <td class="py-2 px-4 flex space-x-2">
                <a href="{{ route('admin.roles.edit', $role) }}" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Edit</a>
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
