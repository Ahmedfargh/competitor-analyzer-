<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\Models\Admin;
use Spatie\Translatable\HasTranslations;

#[ObservedBy([PostObserver::class])]
class Post extends Model
{
    use HasFactory, HasTranslations;

    /**
     * Translatable attribute keys.
     *
     * @var list<string>
     */
    public array $translatable = [
        'title',
        'excerpt',
    ];

    /**
     * Attributes guarded from mass-assignment.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * Get attribute casting definitions.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'blocks' => 'array',
            'seo_meta' => 'array',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Get the author administrator who created the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    /**
     * Get tenant owning this post/page if tenant-scoped.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    /**
     * Scope to published items.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope to landing pages.
     */
    public function scopeLandingPages(Builder $query): Builder
    {
        return $query->where('type', 'landing_page');
    }

    /**
     * Scope to standard blog/article posts.
     */
    public function scopePosts(Builder $query): Builder
    {
        return $query->where('type', 'post');
    }

    /**
     * Scope for a specific tenant or central system.
     */
    public function scopeForTenant(Builder $query, ?string $tenantId = null): Builder
    {
        if ($tenantId === null) {
            return $query->whereNull('tenant_id');
        }

        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Check if post is currently published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && ($this->published_at === null || $this->published_at->isPast());
    }

    /**
     * Check if post is a landing page.
     */
    public function isLandingPage(): bool
    {
        return $this->type === 'landing_page';
    }
}
