<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ContentEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content_type_id',
        'created_by',
        'updated_by',
        'slug',
        'status',
        'published_at',
        'data',
        'meta',
    ];

    protected $casts = [
        'data' => 'array',
        'meta' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title field if exists
        static::creating(function ($entry) {
            if (empty($entry->slug) && isset($entry->data['title'])) {
                $entry->slug = Str::slug($entry->data['title']);
                
                // Ensure uniqueness
                $originalSlug = $entry->slug;
                $count = 1;
                
                while (static::where('slug', $entry->slug)->exists()) {
                    $entry->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        // Create version on update
        static::updated(function ($entry) {
            $entry->createVersion();
        });
    }

    /**
     * Relationships
     */
    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function versions()
    {
        return $this->hasMany(ContentVersion::class)->orderBy('version_number', 'desc');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByType($query, $contentTypeName)
    {
        return $query->whereHas('contentType', function ($q) use ($contentTypeName) {
            $q->where('name', $contentTypeName);
        });
    }

    /**
     * Methods
     */
    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublish()
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function createVersion($summary = null)
    {
        $latestVersion = $this->versions()->max('version_number') ?? 0;

        return ContentVersion::create([
            'content_entry_id' => $this->id,
            'created_by' => auth()->id(),
            'version_number' => $latestVersion + 1,
            'data' => $this->data,
            'meta' => $this->meta,
            'change_summary' => $summary,
        ]);
    }

    public function rollbackToVersion($versionNumber)
    {
        $version = $this->versions()->where('version_number', $versionNumber)->first();

        if (!$version) {
            return false;
        }

        $this->update([
            'data' => $version->data,
            'meta' => $version->meta,
        ]);

        return true;
    }

    /**
     * Get field value
     */
    public function getField($fieldName, $default = null)
    {
        return $this->data[$fieldName] ?? $default;
    }

    /**
     * Set field value
     */
    public function setField($fieldName, $value)
    {
        $data = $this->data;
        $data[$fieldName] = $value;
        $this->data = $data;
        $this->save();
    }
}