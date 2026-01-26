@extends('admin.layouts.admin')

@section('title', 'Media Library')
@section('page-title', 'Media Library')
@section('page-description', 'Upload and manage your media files')

@section('header-actions')
    <a href="{{ route('admin.media.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        Upload Media
    </a>
@endsection

@section('content')

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <!-- Search -->
        <div class="flex-1 min-w-50">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Search files..."
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <!-- Type Filter -->
        <select name="type" 
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            <option value="">All Types</option>
            <option value="images" {{ request('type') === 'images' ? 'selected' : '' }}>Images</option>
            <option value="videos" {{ request('type') === 'videos' ? 'selected' : '' }}>Videos</option>
            <option value="documents" {{ request('type') === 'documents' ? 'selected' : '' }}>Documents</option>
        </select>

        <!-- Folder Filter -->
        @if($folders->count() > 0)
        <select name="folder" 
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            <option value="">All Folders</option>
            @foreach($folders as $folder)
            <option value="{{ $folder }}" {{ request('folder') === $folder ? 'selected' : '' }}>
                {{ $folder }}
            </option>
            @endforeach
        </select>
        @endif

        <!-- Buttons -->
        <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
            Filter
        </button>
        
        @if(request()->hasAny(['search', 'type', 'folder']))
        <a href="{{ route('admin.media.index') }}" 
           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition">
            Clear
        </a>
        @endif
    </form>
</div>

<!-- Media Grid -->
@if($media->count() > 0)
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-6">
    @foreach($media as $item)
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden group">
        <!-- Preview -->
        <div class="aspect-square bg-gray-100 relative overflow-hidden">
            @if($item->isImage())
                <img src="{{ $item->url }}" 
                     alt="{{ $item->alt_text ?? $item->filename }}"
                     class="w-full h-full object-cover">
            @elseif($item->isPdf())
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h12l4 4v16H2v-1z"/>
                        <text x="6" y="14" font-size="8" fill="currentColor">PDF</text>
                    </svg>
                </div>
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif

            <!-- Hover Actions -->
            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center space-x-2">
                <a href="{{ $item->url }}" 
                   target="_blank"
                   class="bg-white text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                <a href="{{ route('admin.media.edit', $item) }}" 
                   class="bg-white text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form action="{{ route('admin.media.destroy', $item) }}" 
                      method="POST" 
                      onsubmit="return confirm('Delete this file?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 text-white p-2 rounded-lg hover:bg-red-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Info -->
        <div class="p-3">
            <p class="text-sm font-medium text-gray-900 truncate" title="{{ $item->filename }}">
                {{ $item->filename }}
            </p>
            <div class="flex items-center justify-between mt-1">
                <span class="text-xs text-gray-500">{{ $item->human_size }}</span>
                <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="bg-white rounded-lg shadow p-4">
    {{ $media->links() }}
</div>

@else
<!-- Empty State -->
<div class="bg-white rounded-lg shadow p-12 text-center">
    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
    <h3 class="text-lg font-medium text-gray-900 mb-2">No media files yet</h3>
    <p class="text-gray-600 mb-4">Upload your first file to get started</p>
    <a href="{{ route('admin.media.create') }}" 
       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        Upload Media
    </a>
</div>
@endif

@endsection