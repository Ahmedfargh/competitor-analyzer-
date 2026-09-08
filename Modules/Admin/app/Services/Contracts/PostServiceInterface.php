<?php

namespace Modules\Admin\Services\Contracts;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\PostDTOInterface;

interface PostServiceInterface
{
    public function getPostById(int $id): ?Post;

    public function getPostBySlug(string $slug, ?string $tenantId = null): ?Post;

    public function getPaginatedPosts(int $perPage = 15, ?string $type = null, ?string $status = null, ?string $search = null, ?string $tenantId = null): LengthAwarePaginator;

    public function createPost(PostDTOInterface $dto): Post;

    public function updatePost(Post $post, PostDTOInterface $dto): Post;

    public function deletePost(Post $post): bool;

    public function duplicatePost(Post $post): Post;

    public function togglePublishStatus(Post $post): Post;

    public function getActiveLandingPages(?string $tenantId = null): Collection;
}
