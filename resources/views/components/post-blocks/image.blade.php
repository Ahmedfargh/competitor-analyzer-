@props(['content' => []])

@php
    $url = $content['url'] ?? '';
    $caption = $content['caption'] ?? '';
    $alt = $content['alt'] ?? 'Content image';
    $ratio = $content['aspect_ratio'] ?? '16:9';
    $aspectClass = match($ratio) {
        '4:3' => 'aspect-[4/3]',
        '1:1' => 'aspect-square',
        'full' => '',
        default => 'aspect-[16/9]',
    };
@endphp

@if($url)
    <figure class="my-8 rounded-2xl overflow-hidden border border-zinc-800 bg-zinc-900/60 p-2 shadow-2xl backdrop-blur-sm">
        <div class="overflow-hidden rounded-xl {{ $aspectClass }} bg-zinc-950 flex items-center justify-center">
            <img src="{{ $url }}" alt="{{ $alt }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" loading="lazy" />
        </div>
        @if($caption)
            <figcaption class="mt-2.5 text-center text-xs text-zinc-500 font-mono italic">
                {{ $caption }}
            </figcaption>
        @endif
    </figure>
@endif
