<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Services\Contracts\PostServiceInterface;

class PublicPostController extends Controller
{
    public function __construct(
        protected PostServiceInterface $postService
    ) {}

    public function show(string $slug, Request $request)
    {
        $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;

        $post = $this->postService->getPostBySlug($slug, $tenantId);

        if (! $post) {
            abort(404, 'Page or post not found.');
        }

        // Only allow viewing published items unless user is authenticated admin
        if (! $post->isPublished() && ! auth('admin')->check()) {
            abort(404, 'This page is currently unpublished.');
        }

        return view('posts.show', compact('post'));
    }
}
