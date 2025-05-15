<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'category',
        'tags',
        'user_id',
        'is_private'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_private' => 'boolean'
    ];

    // Relationship with user (vet)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 