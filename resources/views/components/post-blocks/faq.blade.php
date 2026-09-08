@props(['content' => []])

@php
    $headline = $content['headline'] ?? 'Frequently Asked Questions';
    $items = $content['items'] ?? [];
@endphp

<section class="my-16 py-8 max-w-4xl mx-auto">
    @if($headline)
        <h2 class="text-2xl sm:text-4xl font-extrabold text-white text-center tracking-tight mb-10">{{ $headline }}</h2>
    @endif

    <div class="space-y-4" x-data="{ openItem: null }">
        @foreach($items as $i => $item)
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 overflow-hidden transition-all duration-200 hover:border-zinc-700">
                <button @click="openItem = (openItem === {{ $i }} ? null : {{ $i }})"
                        type="button"
                        class="w-full flex items-center justify-between p-5 sm:p-6 text-left focus:outline-none">
                    <span class="text-base sm:text-lg font-semibold text-zinc-100">{{ $item['question'] ?? '' }}</span>
                    <span class="ml-4 w-7 h-7 rounded-lg bg-zinc-800 flex items-center justify-center text-zinc-400 transition-transform duration-200"
                          :class="{ 'rotate-180 text-orange-400 bg-orange-500/20': openItem === {{ $i }} }">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </button>
                <div x-show="openItem === {{ $i }}" x-cloak x-collapse class="px-5 sm:px-6 pb-6 pt-1 text-sm sm:text-base text-zinc-400 leading-relaxed font-light border-t border-zinc-800/40">
                    {{ $item['answer'] ?? '' }}
                </div>
            </div>
        @endforeach
    </div>
</section>
