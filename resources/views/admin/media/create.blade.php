@extends('admin.layouts.admin')
@section('title', 'Upload Media')
@section('page-title', 'Upload Media')
@section('page-description', 'Upload images, videos, and documents')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- File Upload -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Files</h3>
            
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center hover:border-blue-500 transition">
                <input type="file" 
                       name="files[]" 
                       id="file-input"
                       multiple
                       accept="image/*,video/*,.pdf,.doc,.docx"
                       required
                       class="hidden"
                       onchange="updateFileList()">
                
                <label for="file-input" class="cursor-pointer">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-lg font-medium text-gray-900 mb-2">Click to upload files</p>
                    <p class="text-sm text-gray-600">or drag and drop</p>
                    <p class="text-xs text-gray-500 mt-2">Images, Videos, PDFs up to 50MB</p>
                </label>
            </div>

            <!-- Selected Files Preview -->
            <div id="file-preview" class="mt-4 space-y-2 hidden"></div>

            @error('files.*')
            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Metadata -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">File Information (Optional)</h3>
            
            <div class="space-y-4">
                <!-- Folder -->
                <div>
                    <label for="folder" class="block text-sm font-medium text-gray-700 mb-2">
                        Folder / Category
                    </label>
                    <input type="text" 
                           id="folder" 
                           name="folder" 
                           list="folders-list"
                           value="{{ old('folder') }}"
                           placeholder="e.g., Blog Images, Product Photos"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    
                    @if($folders->count() > 0)
                    <datalist id="folders-list">
                        @foreach($folders as $folder)
                        <option value="{{ $folder }}">
                        @endforeach
                    </datalist>
                    @endif
                    
                    <p class="text-xs text-gray-500 mt-1">Organize files into folders (leave empty for default)</p>
                </div>

                <!-- Alt Text -->
                <div>
                    <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-2">
                        Alt Text (SEO)
                    </label>
                    <input type="text" 
                           id="alt_text" 
                           name="alt_text" 
                           value="{{ old('alt_text') }}"
                           placeholder="Describe the image for accessibility"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Applied to all uploaded images</p>
                </div>

                <!-- Caption -->
                <div>
                    <label for="caption" class="block text-sm font-medium text-gray-700 mb-2">
                        Caption
                    </label>
                    <textarea id="caption" 
                              name="caption" 
                              rows="2"
                              placeholder="Optional caption for the media"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('caption') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.media.index') }}" 
               class="text-gray-600 hover:text-gray-800 font-medium">
                ← Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Upload Files
            </button>
        </div>
    </form>
</div>

<script>
function updateFileList() {
    const input = document.getElementById('file-input');
    const preview = document.getElementById('file-preview');
    const files = input.files;
    
    if (files.length === 0) {
        preview.classList.add('hidden');
        return;
    }
    
    preview.classList.remove('hidden');
    preview.innerHTML = '<p class="text-sm font-medium text-gray-700 mb-2">Selected Files (' + files.length + '):</p>';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        fileItem.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span class="text-sm text-gray-900">${file.name}</span>
            </div>
            <span class="text-xs text-gray-500">${fileSize} MB</span>
        `;
        preview.appendChild(fileItem);
    }
}
</script>
@endsection