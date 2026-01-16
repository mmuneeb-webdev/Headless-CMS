<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create {{ $contentType->display_name }} - Contentra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-3xl font-bold text-gray-900">Create {{ $contentType->display_name }}</h1>
                <p class="text-sm text-gray-600 mt-1">Fill in the fields below</p>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <form action="{{ route('admin.content-entries.store', $contentType) }}" method="POST" class="space-y-6">
                @csrf

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

                        @switch($field->type)
                            @case('string')
                            @case('email')
                            @case('url')
                                <input type="{{ $field->type === 'email' ? 'email' : ($field->type === 'url' ? 'url' : 'text') }}" 
                                       name="data[{{ $field->name }}]" 
                                       value="{{ old('data.' . $field->name) }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       placeholder="Enter {{ strtolower($field->display_name) }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @break

                            @case('text')
                                <textarea name="data[{{ $field->name }}]" 
                                          rows="4"
                                          {{ $field->is_required ? 'required' : '' }}
                                          placeholder="Enter {{ strtolower($field->display_name) }}"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('data.' . $field->name) }}</textarea>
                                @break

                            @case('rich_text')
                                <textarea name="data[{{ $field->name }}]" 
                                          rows="8"
                                          {{ $field->is_required ? 'required' : '' }}
                                          placeholder="Enter rich text content (HTML supported)"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm">{{ old('data.' . $field->name) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">💡 HTML tags are supported</p>
                                @break

                            @case('number')
                                <input type="number" 
                                       name="data[{{ $field->name }}]" 
                                       value="{{ old('data.' . $field->name) }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       step="any"
                                       placeholder="Enter number"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @break

                            @case('boolean')
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="data[{{ $field->name }}]" 
                                           value="1"
                                           {{ old('data.' . $field->name) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                                </label>
                                @break

                            @case('date')
                                <input type="date" 
                                       name="data[{{ $field->name }}]" 
                                       value="{{ old('data.' . $field->name) }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @break

                            @case('datetime')
                                <input type="datetime-local" 
                                       name="data[{{ $field->name }}]" 
                                       value="{{ old('data.' . $field->name) }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @break

                            @case('json')
                                <textarea name="data[{{ $field->name }}]" 
                                          rows="6"
                                          {{ $field->is_required ? 'required' : '' }}
                                          placeholder='{"key": "value"}'
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm">{{ old('data.' . $field->name) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">💡 Enter valid JSON</p>
                                @break

                            @default
                                <input type="text" 
                                       name="data[{{ $field->name }}]" 
                                       value="{{ old('data.' . $field->name) }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @endswitch

                        @error('data.' . $field->name)
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach

                    @if($contentType->fields->count() === 0)
                    <div class="text-center py-8">
                        <p class="text-gray-500">No fields defined for this content type.</p>
                        <a href="{{ route('admin.content-types.edit', $contentType) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">
                            Add fields first →
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Meta Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Publishing Options</h2>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug (URL)</label>
                        <input type="text" 
                               name="slug" 
                               value="{{ old('slug') }}"
                               placeholder="Leave empty to auto-generate from title"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">💡 Auto-generated if left empty</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="status" 
                                       value="draft" 
                                       {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Save as Draft</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="status" 
                                       value="published" 
                                       {{ old('status') === 'published' ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Publish Immediately</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.content-entries.index', $contentType) }}" 
                           class="text-gray-600 hover:text-gray-800 font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                            Create Entry
                        </button>
                    </div>
                </div>
            </form>

        </main>
    </div>
</body>
</html>