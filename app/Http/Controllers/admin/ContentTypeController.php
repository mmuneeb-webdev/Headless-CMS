<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentType;
use App\Models\ContentField;
use Illuminate\Http\Request;

class ContentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-content-types')->only(['index', 'show']);
        $this->middleware('permission:create-content-types')->only(['create', 'store']);
        $this->middleware('permission:edit-content-types')->only(['edit', 'update']);
        $this->middleware('permission:delete-content-types')->only(['destroy']);
    }

    public function index()
    {
        $contentTypes = ContentType::withCount('fields', 'entries')->get();
        return view('admin.content-types.index', compact('contentTypes'));
    }

    public function create()
    {
        return view('admin.content-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'name' => 'nullable|string|max:255|unique:content_types,name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $contentType = ContentType::create($validated);

        return redirect()
            ->route('admin.content-types.edit', $contentType)
            ->with('success', 'Content type created! Now add fields.');
    }

    public function show(ContentType $contentType)
    {
        $contentType->load(['fields', 'entries' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('admin.content-types.show', compact('contentType'));
    }

    public function edit(ContentType $contentType)
    {
        $contentType->load('fields');
        $fieldTypes = ContentField::getFieldTypes();
        
        return view('admin.content-types.edit', compact('contentType', 'fieldTypes'));
    }

    public function update(Request $request, ContentType $contentType)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $contentType->update($validated);

        return redirect()
            ->route('admin.content-types.index')
            ->with('success', 'Content type updated!');
    }

    public function destroy(ContentType $contentType)
    {
        // Check if content type has entries
        if ($contentType->entries()->count() > 0) {
            return redirect()
                ->route('admin.content-types.index')
                ->with('error', 'Cannot delete content type with existing entries!');
        }

        $contentType->delete();

        return redirect()
            ->route('admin.content-types.index')
            ->with('success', 'Content type deleted!');
    }

    /**
     * Add field to content type
     */
    public function addField(Request $request, ContentType $contentType)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(ContentField::getFieldTypes())),
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'is_unique' => 'boolean',
            'is_translatable' => 'boolean',
            'order' => 'nullable|integer',
            'validation_rules' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        $validated['content_type_id'] = $contentType->id;

        // Auto-generate name if not provided
        if (empty($validated['name'])) {
            $validated['name'] = \Illuminate\Support\Str::slug($validated['display_name'], '_');
        }

        // Set order if not provided
        if (!isset($validated['order'])) {
            $maxOrder = $contentType->fields()->max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

        ContentField::create($validated);

        return redirect()
            ->route('admin.content-types.edit', $contentType)
            ->with('success', 'Field added successfully!');
    }

    /**
     * Update field
     */
    public function updateField(Request $request, ContentType $contentType, ContentField $field)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'is_unique' => 'boolean',
            'is_translatable' => 'boolean',
            'order' => 'nullable|integer',
            'validation_rules' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        $field->update($validated);

        return redirect()
            ->route('admin.content-types.edit', $contentType)
            ->with('success', 'Field updated!');
    }

    /**
     * Delete field
     */
    public function deleteField(ContentType $contentType, ContentField $field)
    {
        $field->delete();

        return redirect()
            ->route('admin.content-types.edit', $contentType)
            ->with('success', 'Field deleted!');
    }
}