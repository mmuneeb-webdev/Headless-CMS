<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $contentType->display_name }} - Contentra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit {{ $contentType->display_name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">
                            ID: {{ $entry->id }} | 
                            Created: {{ $entry->created_at->format('M d, Y') }} | 
                            Version: {{ $entry->versions->count() + 1 }}
                        </p>
                    </div>
                    
                    <!-- Status Badge -->
                    @if($entry->status === 'published')
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        Published
                    </span>
                    @else
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                        Draft
                    </span>
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('admin.content-entries.update', [$contentType, $entry]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Dynamic Fields -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Content Fields</h2>
                    
                    @foreach($contentType->fields->sortBy('order') as $field)
                    <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $field->display_name }}
                            @if($field->is_required)
                            <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if($field->description)
                        <p class="text-sm text-gray-500 mb-2">{{ $field->description }}</p>
                        @endif

                        @php
                            $value = old('data.' . $field->name, $entry->getField($field->name));
                        @endphp

                        @switch($field->type)
                            @case('string')
                            @case('email')
                            @case('url')
                                <input type="{{ $field->type === 'email' ? 'email' : ($field->type === 'url' ? 'url' : 'text') }}" 
                                       name="data[{{ $field->name }}]" 
                                       value="{{ $value }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @break

                            @case('text')
                                <textarea name="data[{{ $field->name }}]" 
                                          rows="4"
                                          {{ $field->is_required ? 'required' : '' }}
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $value }}</textarea>
                                @break

                            @case('rich_text')
                                <textarea name="data[{{ $field->name }}]" 
                                          rows="8"
                                          {{ $field->is_required ? 'required' : '' }}
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm">{{ $value }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">💡 HTML tags are supported</p>
                                @break

                            @case('number')
                                <input type="number" 
                                       name="data[{{ $field->name }}]" 
                                       value="{{ $value }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       step="any"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @break

                            @case('boolean')
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="data[{{ $field->name }}]" 
                                           value="1"
                                           {{ $value ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                                </label>
                                @break

                            @case('date')
                                <input type="date" 
                                       name="data[{{ $field->name }}]" 
                                       value="{{ $value }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @break

                            @case('datetime')
                                <input type="datetime-local" 
                                       name="data[{{ $field->name }}]" 
                                       value="{{ $value ? \Carbon\Carbon::parse($value)->format('Y-m-d\TH:i') : '' }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @break

                            @case('json')
                                <textarea name="data[{{ $field->name }}]" 
                                          rows="6"
                                          {{ $field->is_required ? 'required' : '' }}
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm">{{ is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">💡 Enter valid JSON</p>
                                @break

                            @default
                                <input type="text" 
                                       name="data[{{ $field->name }}]" 
                                       value="{{ $value }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @endswitch

                        @error('data.' . $field->name)
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach
                </div>

                <!-- Meta Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Publishing Options</h2>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug (URL)</label>
                        <input type="text" 
                               name="slug" 
                               value="{{ old('slug', $entry->slug) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Actions</label>
                        <div class="flex space-x-3">
                            @if($entry->status === 'draft')
                            <button type="button" 
                                    onclick="document.querySelector('form').action='{{ route('admin.content-entries.publish', [$contentType, $entry]) }}'; document.querySelector('form').submit();"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                Publish Now
                            </button>
                            @else
                            <button type="button" 
                                    onclick="if(confirm('Unpublish this entry?')) { document.querySelector('form').action='{{ route('admin.content-entries.unpublish', [$contentType, $entry]) }}'; document.querySelector('form').submit(); }"
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                Unpublish
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Version History -->
                @if($entry->versions->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Version History ({{ $entry->versions->count() }})</h2>
                    <div class="space-y-2">
                        @foreach($entry->versions->take(5) as $version)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Version {{ $version->version_number }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $version->created_at->format('M d, Y H:i') }} by {{ $version->creator->name ?? 'System' }}
                                </p>
                            </div>
                            <form action="{{ route('admin.content-entries.rollback', [$contentType, $entry, $version->version_number]) }}" 
                                  method="POST"
                                  onsubmit="return confirm('Rollback to version {{ $version->version_number }}?')">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Restore
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.content-entries.index', $contentType) }}" 
                           class="text-gray-600 hover:text-gray-800 font-medium">
                            ← Back to Entries
                        </a>
                        <div class="space-x-3">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </main>
    </div>
</body>
</html>