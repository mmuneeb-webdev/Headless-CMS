@extends('admin.layouts.admin')

@section('title', 'Create Role')
@section('page-title', 'Create New Role')
@section('page-description', 'Define a new role with permissions')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Role Name -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Information</h3>
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Role Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       required
                       placeholder="e.g., Content Manager, Moderator"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Permissions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Permissions 
                <span class="text-sm font-normal text-gray-500">({{ $permissions->count() }} available)</span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($permissions as $permission)
                <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition">
                    <input type="checkbox" 
                           name="permissions[]" 
                           value="{{ $permission->name }}" 
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-3 text-sm text-gray-900">{{ $permission->name }}</span>
                </label>
                @endforeach
            </div>

            @if($permissions->count() === 0)
            <div class="text-center py-8">
                <p class="text-gray-500">No permissions available. Create permissions first.</p>
                <a href="{{ route('admin.permissions.create') }}" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">
                    Create Permission →
                </a>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.roles.index') }}" 
               class="text-gray-600 hover:text-gray-800 font-medium">
                ← Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Create Role
            </button>
        </div>
    </form>
</div>
@endsection