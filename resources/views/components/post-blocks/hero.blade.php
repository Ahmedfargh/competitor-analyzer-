@props(['content' => []])

@php
    $badge = $content['badge'] ?? null;
    $headline = $content['headline'] ?? 'Next-Generation Strategy';
    $subtitle = $content['subtitle'] ?? '';
    $primaryText = $content['cta_primary_text'] ?? null;
    $primaryUrl = $content['cta_primary_url'] ?? '#';
    $secondaryText = $content['cta_secondary_text'] ?? null;
    $secondaryUrl = $content['cta_secondary_url'] ?? '#';
    $align = $content['align'] ?? 'center';
    $isCenter = $align === 'center';
@endphp

<section class="relative py-20 lg:py-32 overflow-hidden">
    <!-- Ambient Glow Background -->
    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
        <div class="w-[600px] h-[350px] bg-gradient-to-tr from-orange-500/20 via-amber-500/10 to-transparent blur-[140px] rounded-full"></div>
    </div>

    <div class="relative max-w-5xl mx-auto px-6 {{ $isCenter ? 'text-center' : 'text-left' }}">
        @if($badge)
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-500/10 border border-orange-500/25 text-orange-400 text-xs font-mono font-semibold tracking-wider uppercase mb-8 shadow-[0_0_20px_rgba(249,115,22,0.15)]">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-ping"></span>
                {{ $badge }}
            </div>
        @endif

        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.1] mb-6">
            <span class="bg-gradient-to-b from-white via-zinc-100 to-zinc-400 bg-clip-text text-transparent">
                {{ $headline }}
            </span>
        </h1>

        @if($subtitle)
            <p class="text-lg sm:text-xl text-zinc-400 max-w-3xl {{ $isCenter ? 'mx-auto' : '' }} mb-10 leading-relaxed font-light">
                {{ $subtitle }}
            </p>
        @endif

        @if($primaryText || $secondaryText)
            <div class="flex flex-wrap items-center {{ $isCenter ? 'justify-center' : 'justify-start' }} gap-4">
                @if($primaryText)
                    <a href="{{ $primaryUrl }}" class="px-7 py-3.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-semibold text-sm shadow-[0_0_30px_rgba(249,115,22,0.35)] transition-all duration-200 transform hover:-translate-y-0.5">
                        {{ $primaryText }}
                    </a>
                @endif
                @if($secondaryText)
                    <a href="{{ $secondaryUrl }}" class="px-7 py-3.5 rounded-xl bg-zinc-900/80 hover:bg-zinc-800 text-zinc-300 hover:text-white border border-zinc-800 font-semibold text-sm transition-all duration-200 backdrop-blur-md">
                        {{ $secondaryText }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
