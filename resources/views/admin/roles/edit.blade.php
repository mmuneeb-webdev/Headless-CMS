@extends('admin.layouts.admin')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role: ' . $role->name)
@section('page-description', 'Modify role permissions')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

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
                       value="{{ old('name', $role->name) }}"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Permissions -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    Permissions
                    <span class="text-sm font-normal text-gray-500">
                        ({{ $role->permissions->count() }} of {{ $permissions->count() }} selected)
                    </span>
                </h3>
                <div class="flex space-x-2">
                    <button type="button" 
                            onclick="document.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = true)"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Select All
                    </button>
                    <span class="text-gray-300">|</span>
                    <button type="button" 
                            onclick="document.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false)"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Deselect All
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($permissions as $permission)
                <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition {{ $role->hasPermissionTo($permission->name) ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
                    <input type="checkbox" 
                           name="permissions[]" 
                           value="{{ $permission->name }}" 
                           {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-3 text-sm text-gray-900">{{ $permission->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.roles.index') }}" 
               class="text-gray-600 hover:text-gray-800 font-medium">
                ← Back to Roles
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Update Role
            </button>
        </div>
    </form>
</div>
@endsection