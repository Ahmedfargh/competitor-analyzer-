@props(['block' => []])

@php
    $type = $block['type'] ?? 'paragraph';
    $content = $block['content'] ?? [];
    $componentName = 'post-blocks.' . str_replace('_', '-', $type);
@endphp

@switch($type)
    @case('hero')
        <x-post-blocks.hero :content="$content" />
        @break
    @case('heading')
        <x-post-blocks.heading :content="$content" />
        @break
    @case('paragraph')
        <x-post-blocks.paragraph :content="$content" />
        @break
    @case('image')
        <x-post-blocks.image :content="$content" />
        @break
    @case('features_grid')
        <x-post-blocks.features-grid :content="$content" />
        @break
    @case('cta_banner')
        <x-post-blocks.cta-banner :content="$content" />
        @break
    @case('faq')
        <x-post-blocks.faq :content="$content" />
        @break
    @case('quote')
        <x-post-blocks.quote :content="$content" />
        @break
    @case('code')
        <x-post-blocks.code :content="$content" />
        @break
    @default
        <x-post-blocks.paragraph :content="$content" />
@endswitch
