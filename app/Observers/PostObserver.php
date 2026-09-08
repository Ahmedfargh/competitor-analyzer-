<?php

namespace App\Observers;

use App\Models\Post;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;

class PostObserver
{
    public function __construct(
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        $title = is_array($post->title) ? ($post->title['en'] ?? reset($post->title)) : $post->title;

        $this->activityLogService->log(
            action: 'post.created',
            description: "New {$post->type} '{$title}' was created (Slug: {$post->slug}).",
            subjectType: Post::class,
            subjectId: (string) $post->id,
            properties: [
                'type' => $post->type,
                'status' => $post->status,
                'slug' => $post->slug,
            ]
        );
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        $title = is_array($post->title) ? ($post->title['en'] ?? reset($post->title)) : $post->title;
        $isPublishEvent = $post->isDirty('status') && $post->status === 'published';

        $this->activityLogService->log(
            action: $isPublishEvent ? 'post.published' : 'post.updated',
            description: $isPublishEvent
                ? "{$post->type} '{$title}' was published."
                : "{$post->type} '{$title}' was updated.",
            subjectType: Post::class,
            subjectId: (string) $post->id,
            properties: $post->getChanges()
        );
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $title = is_array($post->title) ? ($post->title['en'] ?? reset($post->title)) : $post->title;

        $this->activityLogService->log(
            action: 'post.deleted',
            description: "{$post->type} '{$title}' (ID: {$post->id}) was removed.",
            subjectType: Post::class,
            subjectId: (string) $post->id
        );
    }
}
