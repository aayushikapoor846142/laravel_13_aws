<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KbArticle extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'embedding',
        'is_published',
        'view_count',
        'helpful_count'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function incrementViews()
    {
        $this->increment('view_count');
    }

    public function markHelpful()
    {
        $this->increment('helpful_count');
    }
}
