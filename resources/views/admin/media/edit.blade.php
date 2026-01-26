@extends('admin.layouts.admin')

@section('title', 'Edit Media')
@section('page-title', 'Edit Media: ' . $media->filename)
@section('page-description', 'Update media information and metadata')

@section('content')
<div class="max-w-4xl">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Preview -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview</h3>
            
            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-4">
                @if($media->isImage())
                    <img src="{{ $media->url }}" 
                         alt="{{ $media->alt_text ?? $media->filename }}"
                         class="w-full h-full object-contain">
                @elseif($media->isPdf())
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-24 h-24 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h12l4 4v16H2v-1z"/>
                        </svg>
                        <p class="text-2xl font-bold text-red-500 ml-2">PDF</p>
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- File Info -->
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">File Size:</span>
                    <span class="font-medium text-gray-900">{{ $media->human_size }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Type:</span>
                    <span class="font-medium text-gray-900">{{ $media->mime_type }}</span>
                </div>
                @if($media->width && $media->height)
                <div class="flex justify-between">
                    <span class="text-gray-600">Dimensions:</span>
                    <span class="font-medium text-gray-900">{{ $media->width }} × {{ $media->height }}px</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-600">Uploaded:</span>
                    <span class="font-medium text-gray-900">{{ $media->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">By:</span>
                    <span class="font-medium text-gray-900">{{ $media->uploader->name ?? 'Unknown' }}</span>
                </div>
            </div>

            <!-- Download Button -->
            <a href="{{ $media->url }}" 
               download="{{ $media->filename }}"
               class="mt-4 w-full bg-gray-100 hover:bg-gray-200 text-gray-900 px-4 py-2 rounded-lg font-medium transition flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download
            </a>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Information</h3>
            
            <form action="{{ route('admin.media.update', $media) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Filename (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Filename
                    </label>
                    <input type="text" 
                           value="{{ $media->filename }}"
                           disabled
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 text-gray-600">
                </div>

                <!-- Alt Text -->
                <div>
                    <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-2">
                        Alt Text (SEO)
                    </label>
                    <input type="text" 
                           id="alt_text" 
                           name="alt_text" 
                           value="{{ old('alt_text', $media->alt_text) }}"
                           placeholder="Describe the image for accessibility"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('alt_text')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Caption -->
                <div>
                    <label for="caption" class="block text-sm font-medium text-gray-700 mb-2">
                        Caption
                    </label>
                    <textarea id="caption" 
                              name="caption" 
                              rows="3"
                              placeholder="Optional caption"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('caption', $media->caption) }}</textarea>
                    @error('caption')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Folder -->
                <div>
                    <label for="folder" class="block text-sm font-medium text-gray-700 mb-2">
                        Folder / Category
                    </label>
                    <input type="text" 
                           id="folder" 
                           name="folder" 
                           list="folders-list"
                           value="{{ old('folder', $media->folder) }}"
                           placeholder="e.g., Blog Images"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    
                    @if($folders->count() > 0)
                    <datalist id="folders-list">
                        @foreach($folders as $folder)
                        <option value="{{ $folder }}">
                        @endforeach
                    </datalist>
                    @endif
                </div>

                <!-- URL (Copy) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File URL
                    </label>
                    <div class="flex">
                        <input type="text" 
                               id="file-url"
                               value="{{ $media->url }}"
                               readonly
                               class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 bg-gray-50 text-gray-600">
                        <button type="button"
                                onclick="copyUrl()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 rounded-r-lg transition">
                            Copy
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.media.index') }}" 
                       class="text-gray-600 hover:text-gray-800 font-medium">
                        ← Back
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Update Media
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyUrl() {
    const urlInput = document.getElementById('file-url');
    urlInput.select();
    document.execCommand('copy');
    
    // Visual feedback
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Copied!';
    button.classList.add('bg-green-600');
    button.classList.remove('bg-blue-600');
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('bg-green-600');
        button.classList.add('bg-blue-600');
    }, 2000);
}
</script>
@endsection