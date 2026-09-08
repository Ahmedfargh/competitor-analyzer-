@props(['content' => []])

@php
    $text = $content['text'] ?? '';
    $align = $content['align'] ?? 'left';
    $alignClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<div class="my-5 {{ $alignClass }}">
    <p class="text-base sm:text-lg text-zinc-300 leading-relaxed font-light whitespace-pre-line">
        {{ $text }}
    </p>
</div>
