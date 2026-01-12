@extends('admin.layouts.app')

@section('title', 'Permissions')

@section('content')
<h1 class="text-2xl font-bold mb-4">Permissions</h1>

<a href="{{ route('admin.permissions.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">
    Create Permission
</a>

<table class="min-w-full bg-white shadow rounded">
    <thead>
        <tr>
            <th class="py-2 px-4 border-b">ID</th>
            <th class="py-2 px-4 border-b">Name</th>
            <th class="py-2 px-4 border-b">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($permissions as $permission)
            <tr class="hover:bg-gray-100">
                <td class="py-2 px-4 border-b">{{ $permission->id }}</td>
                <td class="py-2 px-4 border-b">{{ $permission->name }}</td>
                <td class="py-2 px-4 border-b space-x-2">
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this permission?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
