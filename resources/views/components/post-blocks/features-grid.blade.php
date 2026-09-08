@props(['content' => []])

@php
    $headline = $content['headline'] ?? null;
    $columns = (int) ($content['columns'] ?? 3);
    $items = $content['items'] ?? [];
    $colClass = match($columns) {
        2 => 'grid-cols-1 md:grid-cols-2',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 md:grid-cols-3',
    };
@endphp

<section class="my-16 py-8">
    @if($headline)
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">{{ $headline }}</h2>
        </div>
    @endif

    <div class="grid {{ $colClass }} gap-6">
        @foreach($items as $item)
            <div class="group relative rounded-2xl bg-zinc-900/50 border border-zinc-800/80 hover:border-orange-500/40 p-7 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_30px_rgba(249,115,22,0.1)]">
                <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">{{ $item['title'] ?? 'Feature' }}</h3>
                <p class="text-sm text-zinc-400 leading-relaxed font-light">{{ $item['description'] ?? '' }}</p>
            </div>
        @endforeach
    </div>
</section>
