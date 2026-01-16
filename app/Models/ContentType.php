<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ContentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'icon',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contentType) {
            if (empty($contentType->name)) {
                $contentType->name = Str::slug($contentType->display_name);
            }
        });
    }

    /**
     * Relationships
     */
    public function fields()
    {
        return $this->hasMany(ContentField::class)->orderBy('order');
    }

    public function entries()
    {
        return $this->hasMany(ContentEntry::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get validation rules for this content type
     */
    public function getValidationRules()
    {
        $rules = [];

        foreach ($this->fields as $field) {
            $fieldRules = [];

            if ($field->is_required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            // Add type-specific validation
            switch ($field->type) {
                case 'string':
                    $fieldRules[] = 'string';
                    if (isset($field->settings['max_length'])) {
                        $fieldRules[] = 'max:' . $field->settings['max_length'];
                    }
                    break;
                case 'text':
                case 'rich_text':
                    $fieldRules[] = 'string';
                    break;
                case 'number':
                    $fieldRules[] = 'numeric';
                    break;
                case 'boolean':
                    $fieldRules[] = 'boolean';
                    break;
                case 'date':
                    $fieldRules[] = 'date';
                    break;
                case 'datetime':
                    $fieldRules[] = 'date';
                    break;
                case 'email':
                    $fieldRules[] = 'email';
                    break;
                case 'url':
                    $fieldRules[] = 'url';
                    break;
                case 'json':
                    $fieldRules[] = 'array';
                    break;
            }

            if ($field->is_unique) {
                $fieldRules[] = 'unique:content_entries,data->' . $field->name;
            }

            // Add custom validation rules if defined
            if (!empty($field->validation_rules)) {
                $customRules = is_array($field->validation_rules) 
                    ? $field->validation_rules 
                    : json_decode($field->validation_rules, true);
                
                if (is_array($customRules)) {
                    $fieldRules = array_merge($fieldRules, $customRules);
                }
            }

            $rules['data.' . $field->name] = $fieldRules;
        }

        return $rules;
    }
}