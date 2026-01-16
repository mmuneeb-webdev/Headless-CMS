<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentType;
use App\Models\ContentEntry;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * List all content types
     */
    public function types()
    {
        $contentTypes = ContentType::active()
            ->withCount('entries')
            ->get();

        return response()->json([
            'content_types' => $contentTypes,
        ]);
    }

    /**
     * Get single content type with fields
     */
    public function typeShow($typeName)
    {
        $contentType = ContentType::where('name', $typeName)
            ->with('fields')
            ->firstOrFail();

        return response()->json([
            'content_type' => $contentType,
        ]);
    }

    /**
     * List entries for a content type
     */
    public function index(Request $request, $typeName)
    {
        $contentType = ContentType::where('name', $typeName)->firstOrFail();

        $query = ContentEntry::where('content_type_id', $contentType->id)
            ->published();

        // Filter by status (admin can see drafts)
        if ($request->has('status') && auth()->check()) {
            $query->where('status', $request->status);
        }

        // Search in data JSON
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('data', 'like', '%' . $search . '%');
        }

        // Filter by field values
        if ($request->has('filter')) {
            foreach ($request->filter as $field => $value) {
                $query->whereJsonContains('data->' . $field, $value);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'created_at' || $sortBy === 'published_at') {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            // Sort by JSON field
            $query->orderByRaw("JSON_EXTRACT(data, '$.{$sortBy}') {$sortOrder}");
        }

        $perPage = min($request->get('per_page', 15), 100);
        $entries = $query->paginate($perPage);

        return response()->json([
            'content_type' => $contentType->name,
            'entries' => $entries,
        ]);
    }

    /**
     * Get single entry by slug or ID
     */
    public function show($typeName, $slugOrId)
    {
        $contentType = ContentType::where('name', $typeName)->firstOrFail();

        $query = ContentEntry::where('content_type_id', $contentType->id);

        // Try to find by slug first, then by ID
        if (is_numeric($slugOrId)) {
            $entry = $query->where('id', $slugOrId)->firstOrFail();
        } else {
            $entry = $query->where('slug', $slugOrId)->firstOrFail();
        }

        // Only show published unless authenticated
        if (!auth()->check() && $entry->status !== 'published') {
            abort(404);
        }

        $entry->load(['contentType.fields', 'creator', 'updater']);

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Create entry via API
     */
    public function store(Request $request, $typeName)
    {
        $contentType = ContentType::where('name', $typeName)->firstOrFail();

        // Validate using content type rules
        $rules = $contentType->getValidationRules();
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

        $entry->createVersion('Created via API');

        return response()->json([
            'message' => 'Content created successfully',
            'entry' => $entry->load('contentType.fields'),
        ], 201);
    }

    /**
     * Update entry via API
     */
    public function update(Request $request, $typeName, $slugOrId)
    {
        $contentType = ContentType::where('name', $typeName)->firstOrFail();

        $query = ContentEntry::where('content_type_id', $contentType->id);

        if (is_numeric($slugOrId)) {
            $entry = $query->where('id', $slugOrId)->firstOrFail();
        } else {
            $entry = $query->where('slug', $slugOrId)->firstOrFail();
        }

        // Validate
        $rules = $contentType->getValidationRules();
        $validated = $request->validate($rules);

        $entry->update([
            'updated_by' => auth()->id(),
            'slug' => $request->slug ?? $entry->slug,
            'data' => $validated['data'],
            'meta' => $request->meta ?? $entry->meta,
        ]);

        return response()->json([
            'message' => 'Content updated successfully',
            'entry' => $entry->load('contentType.fields'),
        ]);
    }

    /**
     * Delete entry via API
     */
    public function destroy($typeName, $slugOrId)
    {
        $contentType = ContentType::where('name', $typeName)->firstOrFail();

        $query = ContentEntry::where('content_type_id', $contentType->id);

        if (is_numeric($slugOrId)) {
            $entry = $query->where('id', $slugOrId)->firstOrFail();
        } else {
            $entry = $query->where('slug', $slugOrId)->firstOrFail();
        }

        $entry->delete();

        return response()->json([
            'message' => 'Content deleted successfully',
        ]);
    }

    /**
     * Publish entry
     */
    public function publish($typeName, $slugOrId)
    {
        $contentType = ContentType::where('name', $typeName)->firstOrFail();

        $query = ContentEntry::where('content_type_id', $contentType->id);

        if (is_numeric($slugOrId)) {
            $entry = $query->where('id', $slugOrId)->firstOrFail();
        } else {
            $entry = $query->where('slug', $slugOrId)->firstOrFail();
        }

        $entry->publish();

        return response()->json([
            'message' => 'Content published',
            'entry' => $entry,
        ]);
    }

    /**
     * Unpublish entry
     */
    public function unpublish($typeName, $slugOrId)
    {
        $contentType = ContentType::where('name', $typeName)->firstOrFail();

        $query = ContentEntry::where('content_type_id', $contentType->id);

        if (is_numeric($slugOrId)) {
            $entry = $query->where('id', $slugOrId)->firstOrFail();
        } else {
            $entry = $query->where('slug', $slugOrId)->firstOrFail();
        }

        $entry->unpublish();

        return response()->json([
            'message' => 'Content unpublished',
            'entry' => $entry,
        ]);
    }
}