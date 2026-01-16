<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Content Type - Contentra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit: {{ $contentType->display_name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">System Name: <code class="bg-gray-100 px-2 py-0.5 rounded">{{ $contentType->name }}</code></p>
                    </div>
                    <a href="{{ route('admin.content-entries.index', $contentType) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        Manage Entries
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Left: Basic Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Basic Information</h2>
                    
                    <form action="{{ route('admin.content-types.update', $contentType) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                            <input type="text" 
                                   name="display_name" 
                                   value="{{ old('display_name', $contentType->display_name) }}"
                                   required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" 
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">{{ old('description', $contentType->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                            <input type="text" 
                                   name="icon" 
                                   value="{{ old('icon', $contentType->icon) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $contentType->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                            Update Content Type
                        </button>
                    </form>
                </div>

                <!-- Right: Add Field Form -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Add New Field</h2>
                    
                    <form action="{{ route('admin.content-types.fields.store', $contentType) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Field Name <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="display_name" 
                                   required
                                   placeholder="e.g., Title, Content, Price"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Field Type <span class="text-red-500">*</span></label>
                            <select name="type" 
                                    required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                                @foreach($fieldTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" 
                                      rows="2"
                                      placeholder="Help text for editors"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div class="mb-4 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_required" value="1" class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-2 text-sm text-gray-700">Required</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_unique" value="1" class="w-4 h-4 text-blue-600 rounded">
                                <span class="ml-2 text-sm text-gray-700">Unique</span>
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                            + Add Field
                        </button>
                    </form>
                </div>
            </div>

            <!-- Fields List -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Fields ({{ $contentType->fields->count() }})</h2>
                
                @if($contentType->fields->count() > 0)
                <div class="space-y-3">
                    @foreach($contentType->fields->sortBy('order') as $field)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <h3 class="font-semibold text-gray-900">{{ $field->display_name }}</h3>
                                    <code class="text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $field->name }}</code>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">{{ $field->getTypeLabel() }}</span>
                                    @if($field->is_required)
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded">Required</span>
                                    @endif
                                    @if($field->is_unique)
                                    <span class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded">Unique</span>
                                    @endif
                                </div>
                                @if($field->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $field->description }}</p>
                                @endif
                            </div>
                            
                            <form action="{{ route('admin.content-types.fields.destroy', [$contentType, $field]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Delete this field?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <p>No fields added yet. Add your first field above.</p>
                </div>
                @endif
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <a href="{{ route('admin.content-types.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Back to Content Types
                </a>
            </div>
        </main>
    </div>
</body>
</html>