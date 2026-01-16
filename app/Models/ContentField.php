<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContentField extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_type_id',
        'name',
        'display_name',
        'type',
        'description',
        'is_required',
        'is_unique',
        'is_translatable',
        'order',
        'validation_rules',
        'settings',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
        'is_translatable' => 'boolean',
        'validation_rules' => 'array',
        'settings' => 'array',
    ];

    /**
     * Available field types
     */
    public static function getFieldTypes()
    {
        return [
            'string' => 'Short Text',
            'text' => 'Long Text',
            'rich_text' => 'Rich Text (WYSIWYG)',
            'number' => 'Number',
            'boolean' => 'Boolean (Yes/No)',
            'date' => 'Date',
            'datetime' => 'Date & Time',
            'email' => 'Email',
            'url' => 'URL',
            'media' => 'Media (Image/Video)',
            'json' => 'JSON',
            'relation' => 'Relation (Link to another content)',
        ];
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($field) {
            if (empty($field->name)) {
                $field->name = Str::slug($field->display_name, '_');
            }
        });
    }

    /**
     * Relationships
     */
    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }

    /**
     * Get field type label
     */
    public function getTypeLabel()
    {
        $types = self::getFieldTypes();
        return $types[$this->type] ?? $this->type;
    }
}