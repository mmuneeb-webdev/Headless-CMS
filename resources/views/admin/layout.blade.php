<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-gray-100 min-h-screen p-6">
            <h1 class="text-xl font-bold mb-6">Admin Dashboard</h1>

            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                    Dashboard
                </a>

                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                    Users
                </a>

                <a href="{{ route('admin.roles.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                    Roles
                </a>

                <a href="{{ route('admin.permissions.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                    Permissions
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full bg-red-600 px-4 py-2 rounded hover:bg-red-700">
                        Logout
                    </button>
                </form>
            </nav>

        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>

</body>

</html>