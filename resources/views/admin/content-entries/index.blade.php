<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $contentType->display_name }} Entries - Contentra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $contentType->display_name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">Manage your content entries</p>
                    </div>
                    <a href="{{ route('admin.content-entries.create', $contentType) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        + Create Entry
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
            @endif

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Total Entries</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $entries->total() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Published</p>
                    <p class="text-2xl font-bold text-green-600">{{ $entries->where('status', 'published')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Drafts</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $entries->where('status', 'draft')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Fields</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $contentType->fields->count() }}</p>
                </div>
            </div>

            <!-- Entries Table -->
            @if($entries->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Content
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Author
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Updated
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($entries as $entry)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $entry->getField('title') ?? $entry->getField('name') ?? 'Entry #' . $entry->id }}
                                    </p>
                                    @if($entry->slug)
                                    <p class="text-sm text-gray-500">
                                        <code class="bg-gray-100 px-1 rounded">{{ $entry->slug }}</code>
                                    </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($entry->status === 'published')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Published
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Draft
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-900">{{ $entry->creator->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ $entry->created_at->format('M d, Y') }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $entry->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.content-entries.edit', [$contentType, $entry]) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    Edit
                                </a>
                                
                                @if($entry->status === 'draft')
                                <form action="{{ route('admin.content-entries.publish', [$contentType, $entry]) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                        Publish
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.content-entries.unpublish', [$contentType, $entry]) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                        Unpublish
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('admin.content-entries.destroy', [$contentType, $entry]) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Delete this entry?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $entries->links() }}
                </div>
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No entries yet</h3>
                <p class="text-gray-600 mb-4">Create your first {{ $contentType->display_name }} entry</p>
                <a href="{{ route('admin.content-entries.create', $contentType) }}" 
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                    + Create Entry
                </a>
            </div>
            @endif

            <!-- Back Button -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('admin.content-types.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Back to Content Types
                </a>
                <a href="{{ route('admin.content-types.edit', $contentType) }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    Edit Content Type →
                </a>
            </div>
        </main>
    </div>
</body>
</html>