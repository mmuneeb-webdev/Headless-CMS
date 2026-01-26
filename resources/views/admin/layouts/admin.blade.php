<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - BACKEND-CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 shrink-0 relative">

            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                {{-- <h1 class="text-2xl font-bold text-gray-900">CMS</h1> --}}
                <p class="text-xs text-gray-500 mt-1">Admin Panel</p>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center px-4 py-3 rounded-lg transition
           {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                    <div
                        class="w-9 h-9 rounded-lg flex items-center justify-center
                {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-gray-200' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-600' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>
                <!-- Divider -->
                <div class="my-3 border-t border-gray-200"></div>
                <!-- Content Types -->
                <a href="{{ route('admin.content-types.index') }}"
                    class="group flex items-center px-4 py-3 rounded-lg transition
           {{ request()->routeIs('admin.content-types.*')
               ? 'bg-yellow-50 text-yellow-700'
               : 'hover:bg-gray-100 text-gray-700' }}">
                    <div
                        class="w-9 h-9 rounded-lg flex items-center justify-center
                {{ request()->routeIs('admin.content-types.*') ? 'bg-yellow-100' : 'bg-gray-100 group-hover:bg-gray-200' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.content-types.*') ? 'text-yellow-600' : 'text-gray-600' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">Content Types</span>
                </a>

                <!-- Divider -->
                <div class="my-3 border-t border-gray-200"></div>
                <!-- Media Library -->
                <a href="{{ route('admin.media.index') }}"
                    class="group flex items-center px-4 py-3 rounded-lg transition
   {{ request()->routeIs('admin.media.*') ? 'bg-purple-50 text-purple-700' : 'hover:bg-gray-100 text-gray-700' }}">
                    <div
                        class="w-9 h-9 rounded-lg flex items-center justify-center
        {{ request()->routeIs('admin.media.*') ? 'bg-purple-100' : 'bg-gray-100 group-hover:bg-gray-200' }}">
                        <svg
                            class="w-5 h-5 {{ request()->routeIs('admin.media.*') ? 'text-purple-600' : 'text-gray-600' }}">
                            <!-- icon -->
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">Media Library</span>
                </a>
                <!-- Divider -->
                <div class="my-3 border-t border-gray-200"></div>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}"
                    class="group flex items-center px-4 py-3 rounded-lg transition
           {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-100 text-gray-700' }}">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">Users</span>
                </a>
                <!-- Divider -->
                <div class="my-3 border-t border-gray-200"></div>
                <!-- Roles -->
                <a href="{{ route('admin.roles.index') }}"
                    class="group flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">Roles</span>
                </a>
                <!-- Divider -->
                <div class="my-3 border-t border-gray-200"></div>
                <!-- Permissions -->
                <a href="{{ route('admin.permissions.index') }}"
                    class="group flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">
                    <div class="w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">Permissions</span>
                </a>

            </nav>
            <!-- Divider -->
            <div class="my-3 border-t border-gray-200"></div>
            <!-- User / Logout -->
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-200 bg-white">
                <div class="flex items-center mb-3">
                    <div
                        class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center font-bold text-blue-700">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ auth()->user()->roles->first()->name ?? 'User' }}
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-600 mt-1">@yield('page-description', '')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @yield('header-actions')
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-8">

                <!-- Success Message -->
                @if (session('success'))
                    <div
                        class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div
                        class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
