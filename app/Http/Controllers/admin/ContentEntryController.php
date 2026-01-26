<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentType;
use App\Models\ContentEntry;
use Illuminate\Http\Request;

class ContentEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-content')->only(['index', 'show']);
        $this->middleware('permission:create-content')->only(['create', 'store']);
        $this->middleware('permission:edit-content')->only(['edit', 'update']);
        $this->middleware('permission:delete-content')->only(['destroy']);
        $this->middleware('permission:publish-content')->only(['publish']);
        $this->middleware('permission:unpublish-content')->only(['unpublish']);
    }

    /**
     * List all entries for a content type
     */
    public function index(ContentType $contentType)
    {
        $entries = ContentEntry::where('content_type_id', $contentType->id)
            ->with(['creator', 'updater'])
            ->latest()
            ->paginate(20);

        return view('admin.content-entries.index', compact('contentType', 'entries'));
    }

    /**
     * Show create form
     */
    public function create(ContentType $contentType)
    {
        $contentType->load('fields');
        return view('admin.content-entries.create', compact('contentType'));
    }

    /**
     * Store new entry
     */
    public function store(Request $request, ContentType $contentType)
    {
        // Get validation rules from content type
        $rules = $contentType->getValidationRules();
        
        // Add slug validation if provided
        if ($request->has('slug')) {
            $rules['slug'] = 'nullable|string|max:255|unique:content_entries,slug';
        }

        $validated = $request->validate($rules);

        $entry = ContentEntry::create([
            'content_type_id' => $contentType->id,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'slug' => $request->slug,
            'status' => $request->status ?? 'draft',
            'data' => $validated['data'],
            'meta' => $request->meta ?? [],
        ]);

        // Create initial version
        $entry->createVersion('Initial creation');

        return redirect()
            ->route('admin.content-entries.edit', [$contentType, $entry])
            ->with('success', 'Content created successfully!');
    }

    /**
     * Show single entry
     */
    public function show(ContentType $contentType, ContentEntry $entry)
    {
        $entry->load(['contentType.fields', 'creator', 'updater', 'versions']);
        return view('admin.content-entries.show', compact('contentType', 'entry'));
    }

    /**
     * Show edit form
     */
    public function edit(ContentType $contentType, ContentEntry $entry)
    {
        $contentType->load('fields');
        $entry->load('versions');
        
        return view('admin.content-entries.edit', compact('contentType', 'entry'));
    }

    /**
     * Update entry
     */
    public function update(Request $request, ContentType $contentType, ContentEntry $entry)
    {
        // Get validation rules
        $rules = $contentType->getValidationRules();
        
        // Slug validation (unique except current)
        if ($request->has('slug')) {
            $rules['slug'] = 'nullable|string|max:255|unique:content_entries,slug,' . $entry->id;
        }

        $validated = $request->validate($rules);

        $entry->update([
            'updated_by' => auth()->id(),
            'slug' => $request->slug,
            'data' => $validated['data'],
            'meta' => $request->meta ?? $entry->meta,
        ]);
        $entry->createVersion('Content updated');

        return redirect()
            ->route('admin.content-entries.edit', [$contentType, $entry])
            ->with('success', 'Content updated successfully!');
    }

    /**
     * Delete entry
     */
    public function destroy(ContentType $contentType, ContentEntry $entry)
    {
        $entry->delete();

        return redirect()
            ->route('admin.content-entries.index', $contentType)
            ->with('success', 'Content deleted!');
    }

    /**
     * Publish entry
     */
    public function publish(ContentType $contentType, ContentEntry $entry)
    {
        $entry->publish();

        return back()->with('success', 'Content published!');
    }

    /**
     * Unpublish entry
     */
    public function unpublish(ContentType $contentType, ContentEntry $entry)
    {
        $entry->unpublish();

        return back()->with('success', 'Content unpublished!');
    }

    /**
     * Rollback to version
     */
    public function rollback(ContentType $contentType, ContentEntry $entry, $versionNumber)
    {
        if ($entry->rollbackToVersion($versionNumber)) {
            return back()->with('success', 'Rolled back to version ' . $versionNumber);
        }

        return back()->with('error', 'Version not found!');
    }
}