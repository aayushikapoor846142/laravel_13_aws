<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RagDocument extends Model
{
    protected $fillable = [
        'title',
        'content',
        'embedding',
        'source',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
