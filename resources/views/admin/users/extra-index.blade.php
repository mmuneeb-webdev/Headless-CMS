@extends('admin.layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users Management')
@section('page-description', 'Manage system users and their roles')

@section('header-actions')
    @can('create-users')
    <a href="{{ route('admin.users.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create User
    </a>
    @endcan
@endsection

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    User
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Email
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Roles
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">ID: {{ $user->id }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <p class="text-sm text-gray-900">{{ $user->email }}</p>
                    @if($user->email_verified_at)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                        Verified
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @can('edit-users')
                    <form method="POST" 
                          action="{{ route('admin.users.roles.update', $user) }}"
                          class="flex flex-wrap gap-2">
                        @csrf
                        @foreach($roles as $role)
                        <label class="inline-flex items-center px-2 py-1 rounded cursor-pointer {{ $user->hasRole($role->name) ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                            <input type="checkbox" 
                                   name="roles[]" 
                                   value="{{ $role->name }}"
                                   {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                   class="w-3 h-3 mr-1">
                            <span class="text-xs font-medium">{{ $role->name }}</span>
                        </label>
                        @endforeach
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <button type="submit" 
                                class="text-green-600 hover:text-green-900 font-medium text-sm">
                            Save Roles
                        </button>
                    </form>
                    @endcan

                    @can('edit-users')
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                        Edit
                    </a>
                    @endcan

                    @can('delete-users')
                    <form action="{{ route('admin.users.destroy', $user) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm">
                            Delete
                        </button>
                    </form>
                    @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <p class="text-gray-500 font-medium">No users found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
</div>
@endsection