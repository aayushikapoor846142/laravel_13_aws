<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportQuery extends Model
{
    protected $fillable = [
        'user_id',
        'question',
        'answer',
        'sources',
        'was_helpful',
        'feedback'
    ];

    protected $casts = [
        'sources' => 'array',
        'was_helpful' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
