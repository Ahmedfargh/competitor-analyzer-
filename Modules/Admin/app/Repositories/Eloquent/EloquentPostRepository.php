<?php

namespace Modules\Admin\Repositories\Eloquent;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\PostDTOInterface;
use Modules\Admin\Repositories\Contracts\PostRepositoryInterface;

class EloquentPostRepository implements PostRepositoryInterface
{
    public function findById(int $id): ?Post
    {
        return Post::with(['author', 'tenant'])->find($id);
    }

    public function findBySlug(string $slug, ?string $tenantId = null): ?Post
    {
        return Post::with(['author', 'tenant'])
            ->where('slug', $slug)
            ->forTenant($tenantId)
            ->first();
    }

    public function paginate(int $perPage = 15, ?string $type = null, ?string $status = null, ?string $search = null, ?string $tenantId = null): LengthAwarePaginator
    {
        return Post::with(['author', 'tenant'])
            ->forTenant($tenantId)
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('slug', 'like', "%{$search}%")
                        ->orWhere('title->en', 'like', "%{$search}%")
                        ->orWhere('title->ar', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate($perPage);
    }

    public function create(PostDTOInterface $dto): Post
    {
        return Post::create($dto->toArray());
    }

    public function update(Post $post, PostDTOInterface $dto): Post
    {
        $post->update($dto->toArray());

        return $post->fresh(['author', 'tenant']);
    }

    public function delete(Post $post): bool
    {
        return (bool) $post->delete();
    }

    public function duplicate(Post $post): Post
    {
        $clone = $post->replicate();
        $clone->slug = $post->slug.'-copy-'.uniqid();
        $clone->status = 'draft';
        $clone->published_at = null;

        $title = $post->getTranslations('title');
        if (is_array($title)) {
            foreach ($title as $locale => $val) {
                $title[$locale] = $val.' (Copy)';
            }
            $clone->title = $title;
        } else {
            $clone->title = $post->title.' (Copy)';
        }

        $clone->save();

        return $clone;
    }

    public function getPublishedLandingPages(?string $tenantId = null): Collection
    {
        return Post::landingPages()
            ->published()
            ->forTenant($tenantId)
            ->latest('published_at')
            ->get();
    }
}
