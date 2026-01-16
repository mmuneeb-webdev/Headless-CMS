<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Types - Contentra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Content Types</h1>
                        <p class="text-sm text-gray-600 mt-1">Define and manage your content schemas</p>
                    </div>
                    <a href="{{ route('admin.content-types.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        + Create Content Type
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Success Message -->
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
            @endif

            <!-- Content Types Grid -->
            @if($contentTypes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($contentTypes as $contentType)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border border-gray-200">
                    <!-- Icon & Name -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $contentType->display_name }}</h3>
                                <p class="text-sm text-gray-500">{{ $contentType->name }}</p>
                            </div>
                        </div>
                        
                        <!-- Active Badge -->
                        @if($contentType->is_active)
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Active</span>
                        @else
                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">Inactive</span>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($contentType->description)
                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($contentType->description, 100) }}</p>
                    @endif

                    <!-- Stats -->
                    <div class="flex items-center space-x-4 mb-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            {{ $contentType->fields_count }} fields
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            {{ $contentType->entries_count }} entries
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.content-entries.index', $contentType) }}" 
                           class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 text-center px-3 py-2 rounded text-sm font-medium transition">
                            Manage Entries
                        </a>
                        <a href="{{ route('admin.content-types.edit', $contentType) }}" 
                           class="bg-gray-50 hover:bg-gray-100 text-gray-700 px-3 py-2 rounded text-sm font-medium transition">
                            Edit
                        </a>
                        <form action="{{ route('admin.content-types.destroy', $contentType) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure? This will delete all entries!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded text-sm font-medium transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No content types yet</h3>
                <p class="text-gray-600 mb-4">Get started by creating your first content type</p>
                <a href="{{ route('admin.content-types.create') }}" 
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                    + Create Content Type
                </a>
            </div>
            @endif

            <!-- Back to Dashboard -->
            <div class="mt-8">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Back to Dashboard
                </a>
            </div>
        </main>
    </div>
</body>
</html>