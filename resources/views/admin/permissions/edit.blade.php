@extends('admin.layouts.admin')

@section('title', 'Edit Permission')
@section('page-title', 'Edit Permission: ' . $permission->name)
@section('page-description', 'Update permission name')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Permission Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Permission Information</h3>
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Permission Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $permission->name) }}"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">
                    <strong>Guard:</strong> {{ $permission->guard_name }}
                </p>
                <p class="text-sm text-gray-600 mt-1">
                    <strong>Created:</strong> {{ $permission->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>

        <!-- Warning Box -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="text-sm text-yellow-800">
                    <p class="font-medium">Caution</p>
                    <p class="mt-1">Changing this permission name will update it for both 'web' and 'api' guards. Make sure no roles are currently using this permission or update them accordingly.</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.permissions.index') }}" 
               class="text-gray-600 hover:text-gray-800 font-medium">
                ← Back to Permissions
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Update Permission
            </button>
        </div>
    </form>
</div>
@endsection