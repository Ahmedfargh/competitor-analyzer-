<?php

namespace Modules\Admin\Repositories\Contracts;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\PostDTOInterface;

interface PostRepositoryInterface
{
    public function findById(int $id): ?Post;

    public function findBySlug(string $slug, ?string $tenantId = null): ?Post;

    public function paginate(int $perPage = 15, ?string $type = null, ?string $status = null, ?string $search = null, ?string $tenantId = null): LengthAwarePaginator;

    public function create(PostDTOInterface $dto): Post;

    public function update(Post $post, PostDTOInterface $dto): Post;

    public function delete(Post $post): bool;

    public function duplicate(Post $post): Post;

    public function getPublishedLandingPages(?string $tenantId = null): Collection;
}
