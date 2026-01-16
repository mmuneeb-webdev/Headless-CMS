@extends('admin.layouts.admin')

@section('title', 'Create Permission')
@section('page-title', 'Create New Permission')
@section('page-description', 'Add a new permission to the system')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-6">
        @csrf

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
                       value="{{ old('name') }}"
                       required
                       placeholder="e.g., manage-content, delete-posts"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Use kebab-case format (lowercase with hyphens)</p>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-medium">Permission Naming Convention</p>
                    <p class="mt-1">Permissions are created for both 'web' and 'api' guards automatically.</p>
                    <p class="mt-1">Examples: view-users, create-content, delete-roles</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.permissions.index') }}" 
               class="text-gray-600 hover:text-gray-800 font-medium">
                ← Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Create Permission
            </button>
        </div>
    </form>
</div>
@endsection