<?php

namespace Modules\Admin\DTOs\Post;

use Modules\Admin\DTOs\Contracts\PostDTOInterface;

class PostDTO implements PostDTOInterface
{
    /**
     * @param  string|array<string, string>  $title
     * @param  string|array<string, string>|null  $excerpt
     * @param  array<mixed>  $blocks
     * @param  array<string, mixed>  $seoMeta
     */
    public function __construct(
        public readonly string|array $title,
        public readonly string $slug,
        public readonly string $type = 'post',
        public readonly string $status = 'draft',
        public readonly string|array|null $excerpt = null,
        public readonly ?string $featuredImage = null,
        public readonly array $blocks = [],
        public readonly array $seoMeta = [],
        public readonly ?string $publishedAt = null,
        public readonly ?int $authorId = null,
        public readonly ?string $tenantId = null
    ) {}

    public function getTitle(): string|array
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getExcerpt(): string|array|null
    {
        return $this->excerpt;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }

    public function getBlocks(): array
    {
        return $this->blocks;
    }

    public function getSeoMeta(): array
    {
        return $this->seoMeta;
    }

    public function getPublishedAt(): ?string
    {
        return $this->publishedAt;
    }

    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    public function getTenantId(): ?string
    {
        return $this->tenantId;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'type' => $this->type,
            'status' => $this->status,
            'excerpt' => $this->excerpt,
            'featured_image' => $this->featuredImage,
            'blocks' => $this->blocks,
            'seo_meta' => $this->seoMeta,
            'published_at' => $this->publishedAt,
            'author_id' => $this->authorId,
            'tenant_id' => $this->tenantId,
        ];
    }
}
