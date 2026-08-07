<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTranslation extends Model
{
    protected $fillable = [
        'post_id', 'locale', 'title', 'slug', 'excerpt', 'content', 'meta_title', 'meta_description',
        'meta_keywords', 'focus_keyword', 'source_hash', 'translated_at', 'translation_provider',
    ];

    protected function casts(): array
    {
        return ['translated_at' => 'datetime'];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
