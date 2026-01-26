<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-media')->only(['index', 'show']);
        $this->middleware('permission:upload-media')->only(['create', 'store']);
        $this->middleware('permission:delete-media')->only(['destroy']);
    }

    /**
     * Display media library
     */
    public function index(Request $request)
    {
        $query = Media::with('uploader')->latest();

        // Filter by type
        if ($request->has('type')) {
            switch ($request->type) {
                case 'images':
                    $query->images();
                    break;
                case 'videos':
                    $query->videos();
                    break;
                case 'documents':
                    $query->documents();
                    break;
            }
        }

        // Filter by folder
        if ($request->has('folder')) {
            $query->inFolder($request->folder);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('filename', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%")
                  ->orWhere('caption', 'like', "%{$search}%");
            });
        }

        $media = $query->paginate(24);
        $folders = Media::distinct()->pluck('folder')->filter();

        return view('admin.media.index', compact('media', 'folders'));
    }

    /**
     * Show upload form
     */
    public function create()
    {
        $folders = Media::distinct()->pluck('folder')->filter();
        return view('admin.media.create', compact('folders'));
    }

    /**
     * Upload and store media
     */
    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:51200', // 50MB max
            'folder' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string',
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            // Generate unique filename
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $basename = pathinfo($filename, PATHINFO_FILENAME);
            $uniqueName = Str::slug($basename) . '-' . time() . '.' . $extension;

            // Determine folder path
            $folder = $request->folder ? Str::slug($request->folder) : 'uploads';
            $path = $folder . '/' . $uniqueName;

            // Store file
            $storedPath = $file->storeAs($folder, $uniqueName, 'public');

            // Get file info
            $mimeType = $file->getMimeType();
            $size = $file->getSize();

            // Get image dimensions if it's an image
            $width = null;
            $height = null;

            if (str_starts_with($mimeType, 'image/')) {
                try {
                    $imagePath = Storage::disk('public')->path($storedPath);
                    list($width, $height) = getimagesize($imagePath);
                } catch (\Exception $e) {
                    // Ignore if can't get dimensions
                }
            }

            // Create media record
            $media = Media::create([
                'uploaded_by' => auth()->id(),
                'filename' => $filename,
                'disk' => 'public',
                'path' => $storedPath,
                'mime_type' => $mimeType,
                'size' => $size,
                'extension' => $extension,
                'width' => $width,
                'height' => $height,
                'alt_text' => $request->alt_text,
                'caption' => $request->caption,
                'folder' => $request->folder,
            ]);

            $uploadedFiles[] = $media;
        }

        $message = count($uploadedFiles) === 1 
            ? 'File uploaded successfully!' 
            : count($uploadedFiles) . ' files uploaded successfully!';

        return redirect()->route('admin.media.index')
            ->with('success', $message);
    }

    /**
     * Show single media item
     */
    public function show(Media $media)
    {
        $media->load('uploader');
        return view('admin.media.show', compact('media'));
    }

    /**
     * Show edit form
     */
    public function edit(Media $media)
    {
        $folders = Media::distinct()->pluck('folder')->filter();
        return view('admin.media.edit', compact('media', 'folders'));
    }

    /**
     * Update media metadata
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string',
            'folder' => 'nullable|string|max:255',
        ]);

        $media->update([
            'alt_text' => $request->alt_text,
            'caption' => $request->caption,
            'folder' => $request->folder,
        ]);

        return redirect()->route('admin.media.index')
            ->with('success', 'Media updated successfully!');
    }

    /**
     * Delete media
     */
    public function destroy(Media $media)
    {
        // This will trigger the model's boot method to delete the file
        $media->forceDelete();

        return redirect()->route('admin.media.index')
            ->with('success', 'Media deleted successfully!');
    }

    /**
     * API: Get media for picker
     */
    public function picker(Request $request)
    {
        $query = Media::latest();

        if ($request->has('type')) {
            if ($request->type === 'image') {
                $query->images();
            }
        }

        $media = $query->paginate(20);

        return response()->json($media);
    }
}