@extends('admin.layout')

@section('title', 'Users')

@section('content')
<table class="min-w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="py-2 px-4 text-left">ID</th>
            <th class="py-2 px-4 text-left">Name</th>
            <th class="py-2 px-4 text-left">Email</th>
            <th class="py-2 px-4 text-left">Roles</th>
        </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\User::all() as $user)
        <tr class="border-b">
            <td class="py-2 px-4">{{ $user->id }}</td>
            <td class="py-2 px-4">{{ $user->name }}</td>
            <td class="py-2 px-4">{{ $user->email }}</td>
            <td class="py-2 px-4">
                @foreach($user->roles as $role)
                    <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">{{ $role->name }}</span>
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
