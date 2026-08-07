<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use App\Support\GeneratesSlugs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use GeneratesSlugs;
    use HasFactory;
    use HasLocalizedContent;

    protected string $slugSource = 'title';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'focus_keyword',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class);
    }

    public function internalLinks(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'post_internal_links',
            'source_post_id',
            'target_post_id'
        )->withPivot('position')->orderByPivot('position');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeAvailableInLocale(Builder $query, ?string $locale = null): Builder
    {
        $locale = $locale ?: current_locale();

        if ($locale === config('locales.default', 'vi')) {
            return $query;
        }

        return $query->whereHas('translations', fn (Builder $translationQuery) => $translationQuery
            ->where('locale', $locale)
            ->whereNotNull('slug')->where('slug', '!=', '')
            ->whereNotNull('title')->where('title', '!=', '')
            ->whereNotNull('content')->where('content', '!=', ''));
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
