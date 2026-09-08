<?php

namespace App\Livewire\Admin\Posts;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Admin\Services\Contracts\PostServiceInterface;

class PostManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $typeFilter = '';

    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function togglePublish(int $id, PostServiceInterface $postService): void
    {
        $post = $postService->getPostById($id);
        if ($post) {
            $postService->togglePublishStatus($post);
            session()->flash('success', "Post '{$post->slug}' status updated.");
        }
    }

    public function duplicate(int $id, PostServiceInterface $postService): void
    {
        $post = $postService->getPostById($id);
        if ($post) {
            $clone = $postService->duplicatePost($post);
            session()->flash('success', "Cloned as draft: '{$clone->slug}'");
        }
    }

    public function delete(int $id, PostServiceInterface $postService): void
    {
        $post = $postService->getPostById($id);
        if ($post) {
            $postService->deletePost($post);
            session()->flash('success', "Post '{$post->slug}' was removed.");
        }
    }

    public function render(PostServiceInterface $postService)
    {
        $posts = $postService->getPaginatedPosts(
            perPage: 12,
            type: $this->typeFilter ?: null,
            status: $this->statusFilter ?: null,
            search: $this->search ?: null
        );

        return view('livewire.admin.posts.post-manager', [
            'posts' => $posts,
        ])->layout('admin::components.layouts.master', [
            'title' => __('admin.content_management') ?? 'Landing Pages & Posts',
        ]);
    }
}
