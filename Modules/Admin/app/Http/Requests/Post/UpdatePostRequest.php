<?php

namespace Modules\Admin\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Admin\DTOs\Contracts\PostDTOInterface;
use Modules\Admin\DTOs\Post\PostDTO;
use Modules\Admin\Http\Requests\Contracts\UpdatePostRequestInterface;

class UpdatePostRequest extends FormRequest implements UpdatePostRequestInterface
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required',
            'slug' => 'required|string|max:255',
            'type' => 'required|in:post,landing_page',
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable',
            'featured_image' => 'nullable|string',
            'blocks' => 'nullable|array',
            'seo_meta' => 'nullable|array',
            'published_at' => 'nullable|date',
            'tenant_id' => 'nullable|string|exists:tenants,id',
        ];
    }

    public function toDTO(): PostDTOInterface
    {
        $post = $this->route('post');

        return new PostDTO(
            title: $this->input('title'),
            slug: $this->input('slug'),
            type: $this->input('type', 'post'),
            status: $this->input('status', 'draft'),
            excerpt: $this->input('excerpt'),
            featuredImage: $this->input('featured_image'),
            blocks: $this->input('blocks', []),
            seoMeta: $this->input('seo_meta', []),
            publishedAt: $this->input('published_at'),
            authorId: $post?->author_id ?? auth('admin')->id(),
            tenantId: $this->input('tenant_id')
        );
    }
}
