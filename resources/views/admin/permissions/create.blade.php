@extends('admin.layouts.app')

@section('title', 'Create Permission')

@section('content')
<h1 class="text-2xl font-bold mb-4">Create Permission</h1>

<form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label for="name" class="block font-medium mb-1">Permission Name</label>
        <input type="text" name="name" id="name" 
               class="w-full px-4 py-2 border rounded"
               placeholder="Enter permission name" required>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Create Permission
    </button>
</form>
@endsection
