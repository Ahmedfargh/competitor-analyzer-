<x-admin::layouts.master :title="'Edit ' . ucwords(str_replace('_', ' ', $post->type))">
    <livewire:admin.posts.post-block-editor :post-id="$post->id" :type="$post->type" />
</x-admin::layouts.master>
