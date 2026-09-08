<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $title = $post->getTranslation('title', app()->getLocale(), false) ?: $post->title;
        $seoTitle = $post->seo_meta['meta_title'] ?? $title;
        $seoDesc = $post->seo_meta['meta_description'] ?? ($post->getTranslation('excerpt', app()->getLocale(), false) ?: 'Competitor Intelligence & Market Tracking');
    @endphp

    <title>{{ $seoTitle }} — Compitator</title>
    <meta name="description" content="{{ $seoDesc }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    @if($post->featured_image)
        <meta property="og:image" content="{{ $post->featured_image }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans antialiased min-h-screen selection:bg-orange-500 selection:text-white flex flex-col justify-between">
    <!-- Navbar -->
    <header class="sticky top-0 z-50 border-b border-zinc-800/80 bg-zinc-950/80 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-orange-500 to-amber-500 flex items-center justify-center text-white font-black text-sm shadow-[0_0_20px_rgba(249,115,22,0.4)]">
                    C
                </div>
                <span class="font-extrabold text-base tracking-tight text-white">Compitator</span>
            </a>

            <div class="flex items-center gap-4">
                @if(auth('admin')->check())
                    <a href="{{ route('admin.posts.edit', $post->id) }}"
                       class="px-3 py-1.5 rounded-lg bg-orange-500/10 border border-orange-500/20 text-orange-400 text-xs font-mono font-semibold hover:bg-orange-500 hover:text-white transition-all">
                        Edit in Block Editor
                    </a>
                @endif
                <a href="{{ route('home') }}" class="text-xs text-zinc-400 hover:text-white transition-colors">
                    Back to Main
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-5xl mx-auto px-6 py-12 w-full">
        @if(! $post->isLandingPage())
            <!-- Post Header for standard articles -->
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 text-xs font-mono text-orange-400 uppercase tracking-wider mb-4 px-3 py-1 rounded-full bg-orange-500/10 border border-orange-500/20">
                    <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</span>
                    <span>•</span>
                    <span>{{ $post->author?->name ?? 'Compitator Team' }}</span>
                </div>
                <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight">
                    {{ $title }}
                </h1>
            </div>
        @endif

        <!-- Render All Gutenberg Blocks -->
        <article class="space-y-4">
            @foreach(($post->blocks ?? []) as $block)
                <x-post-blocks.renderer :block="$block" />
            @endforeach
        </article>
    </main>

    <!-- Footer -->
    <footer class="border-t border-zinc-900 bg-zinc-950 py-10 text-center text-xs text-zinc-600 font-mono">
        <p>© {{ date('Y') }} Compitator Intelligence Systems. All rights reserved.</p>
    </footer>
</body>
</html>
