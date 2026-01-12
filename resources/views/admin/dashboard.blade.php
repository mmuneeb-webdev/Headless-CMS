@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded shadow">
        <h2 class="font-semibold text-gray-700">Total Users</h2>
        <p class="mt-2 text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="font-semibold text-gray-700">Total Roles</h2>
        <p class="mt-2 text-2xl font-bold text-gray-900">{{ \Spatie\Permission\Models\Role::count() }}</p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="font-semibold text-gray-700">Total Permissions</h2>
        <p class="mt-2 text-2xl font-bold text-gray-900">{{ \Spatie\Permission\Models\Permission::count() }}</p>
    </div>
</div>
@endsection
