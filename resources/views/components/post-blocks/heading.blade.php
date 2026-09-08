@props(['content' => []])

@php
    $level = $content['level'] ?? 'h2';
    $text = $content['text'] ?? '';
    $align = $content['align'] ?? 'left';
    $alignClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<div class="my-6 {{ $alignClass }}">
    @switch($level)
        @case('h1')
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">{{ $text }}</h1>
            @break
        @case('h3')
            <h3 class="text-xl sm:text-2xl font-bold text-zinc-100 tracking-tight">{{ $text }}</h3>
            @break
        @case('h4')
            <h4 class="text-lg sm:text-xl font-semibold text-zinc-200 tracking-tight">{{ $text }}</h4>
            @break
        @default
            <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight leading-snug">{{ $text }}</h2>
    @endswitch
</div>
