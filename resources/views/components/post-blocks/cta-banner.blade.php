@props(['content' => []])

@php
    $headline = $content['headline'] ?? 'Ready to get started?';
    $description = $content['description'] ?? '';
    $buttonText = $content['button_text'] ?? 'Get Started';
    $buttonUrl = $content['button_url'] ?? '#';
@endphp

<section class="my-16 relative rounded-3xl overflow-hidden border border-orange-500/30 bg-gradient-to-r from-orange-500/10 via-zinc-900 to-amber-500/10 p-8 sm:p-12 lg:p-16 shadow-[0_0_50px_rgba(249,115,22,0.15)] text-center">
    <div class="relative z-10 max-w-2xl mx-auto">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
            {{ $headline }}
        </h2>
        @if($description)
            <p class="text-base sm:text-lg text-zinc-300 font-light mb-8 leading-relaxed">
                {{ $description }}
            </p>
        @endif
        <a href="{{ $buttonUrl }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold text-sm shadow-[0_0_30px_rgba(249,115,22,0.4)] transition-all duration-200 transform hover:-translate-y-0.5">
            <span>{{ $buttonText }}</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>
</section>
