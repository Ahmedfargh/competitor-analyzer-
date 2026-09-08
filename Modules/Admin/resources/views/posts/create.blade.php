<x-admin::layouts.master :title="'New ' . ucwords(str_replace('_', ' ', $type))">
    <livewire:admin.posts.post-block-editor :type="$type" />
</x-admin::layouts.master>
