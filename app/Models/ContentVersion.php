<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_entry_id',
        'created_by',
        'version_number',
        'data',
        'meta',
        'change_summary',
    ];

    protected $casts = [
        'data' => 'array',
        'meta' => 'array',
    ];

    /**
     * Relationships
     */
    public function contentEntry()
    {
        return $this->belongsTo(ContentEntry::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get differences from previous version
     */
    public function getDifferences()
    {
        $previousVersion = $this->contentEntry
            ->versions()
            ->where('version_number', '<', $this->version_number)
            ->orderBy('version_number', 'desc')
            ->first();

        if (!$previousVersion) {
            return ['type' => 'initial', 'changes' => []];
        }

        $changes = [];
        $oldData = $previousVersion->data;
        $newData = $this->data;

        // Find changed fields
        foreach ($newData as $key => $value) {
            if (!isset($oldData[$key]) || $oldData[$key] !== $value) {
                $changes[$key] = [
                    'old' => $oldData[$key] ?? null,
                    'new' => $value,
                ];
            }
        }

        // Find removed fields
        foreach ($oldData as $key => $value) {
            if (!isset($newData[$key])) {
                $changes[$key] = [
                    'old' => $value,
                    'new' => null,
                ];
            }
        }

        return [
            'type' => 'update',
            'changes' => $changes,
        ];
    }
}