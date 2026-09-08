@props(['content' => []])

@php
    $quote = $content['quote'] ?? '';
    $author = $content['author'] ?? '';
    $citation = $content['citation'] ?? '';
@endphp

<blockquote class="my-10 relative pl-6 sm:pl-8 border-l-4 border-orange-500 bg-zinc-900/30 py-4 pr-6 rounded-r-2xl">
    <p class="text-lg sm:text-xl italic font-light text-zinc-200 leading-relaxed">
        “{{ $quote }}”
    </p>
    @if($author)
        <footer class="mt-4 flex items-center gap-3">
            <div class="text-sm font-semibold text-white">{{ $author }}</div>
            @if($citation)
                <span class="text-xs text-zinc-500 font-mono">— {{ $citation }}</span>
            @endif
        </footer>
    @endif
</blockquote>
