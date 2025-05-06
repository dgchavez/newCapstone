<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'title',
        'type',
        'content',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean'
    ];

    protected static function booted()
    {
        static::updating(function ($policy) {
            // If this policy is being published, unpublish others of same type
            if ($policy->is_published && $policy->isDirty('is_published')) {
                self::where('type', $policy->type)
                    ->where('id', '!=', $policy->id)
                    ->update(['is_published' => false]);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}