@extends('admin.layout')

@section('title', 'Edit Role')

@section('content')
<h2 class="text-2xl font-bold mb-4">Edit Role: {{ $role->name }}</h2>

<form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')
    <div>
        <label class="block font-medium mb-1">Role Name</label>
        <input type="text" name="name" value="{{ $role->name }}" class="w-full border border-gray-300 px-4 py-2 rounded">
    </div>

    <div>
        <label class="block font-medium mb-1">Permissions</label>
        <div class="grid grid-cols-3 gap-2">
            @foreach($permissions as $permission)
            <label class="inline-flex items-center">
                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-checkbox h-4 w-4 text-blue-600"
                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                <span class="ml-2">{{ $permission->name }}</span>
            </label>
            @endforeach
        </div>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
</form>
@endsection
