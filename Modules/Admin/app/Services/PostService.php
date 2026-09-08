<?php

namespace Modules\Admin\Services;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\PostDTOInterface;
use Modules\Admin\DTOs\Post\PostDTO;
use Modules\Admin\Repositories\Contracts\PostRepositoryInterface;
use Modules\Admin\Services\Contracts\PostServiceInterface;

class PostService implements PostServiceInterface
{
    public function __construct(
        protected PostRepositoryInterface $postRepo
    ) {}

    public function getPostById(int $id): ?Post
    {
        return $this->postRepo->findById($id);
    }

    public function getPostBySlug(string $slug, ?string $tenantId = null): ?Post
    {
        return $this->postRepo->findBySlug($slug, $tenantId);
    }

    public function getPaginatedPosts(int $perPage = 15, ?string $type = null, ?string $status = null, ?string $search = null, ?string $tenantId = null): LengthAwarePaginator
    {
        return $this->postRepo->paginate($perPage, $type, $status, $search, $tenantId);
    }

    public function createPost(PostDTOInterface $dto): Post
    {
        return $this->postRepo->create($dto);
    }

    public function updatePost(Post $post, PostDTOInterface $dto): Post
    {
        return $this->postRepo->update($post, $dto);
    }

    public function deletePost(Post $post): bool
    {
        return $this->postRepo->delete($post);
    }

    public function duplicatePost(Post $post): Post
    {
        return $this->postRepo->duplicate($post);
    }

    public function togglePublishStatus(Post $post): Post
    {
        $newStatus = $post->status === 'published' ? 'draft' : 'published';
        $publishedAt = $newStatus === 'published' ? ($post->published_at ?? now()) : null;

        $dto = new PostDTO(
            title: $post->getTranslations('title'),
            slug: $post->slug,
            type: $post->type,
            status: $newStatus,
            excerpt: $post->getTranslations('excerpt'),
            featuredImage: $post->featured_image,
            blocks: $post->blocks ?? [],
            seoMeta: $post->seo_meta ?? [],
            publishedAt: $publishedAt?->toDateTimeString(),
            authorId: $post->author_id,
            tenantId: $post->tenant_id
        );

        return $this->postRepo->update($post, $dto);
    }

    public function getActiveLandingPages(?string $tenantId = null): Collection
    {
        return $this->postRepo->getPublishedLandingPages($tenantId);
    }
}
