<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Compitator AI — Autonomous Competitor Analysis & Web Scraping' }}</title>
    <meta name="description" content="{{ $description ?? 'Turn competitor moves into your strategic advantage. Autonomous AI web scraping that monitors pricing, product changes, and positioning in real-time.' }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color-scheme: dark;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Ambient Glow & Horizon Arc */
        .glow-horizon {
            background: radial-gradient(ellipse 70% 35% at 50% 100%, rgba(249, 115, 22, 0.35) 0%, rgba(234, 88, 12, 0.15) 35%, transparent 70%);
        }

        .glow-sphere {
            background: radial-gradient(circle at 50% 50%, rgba(249, 115, 22, 0.18) 0%, rgba(234, 88, 12, 0.05) 50%, transparent 80%);
        }

        .grid-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Custom subtle scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #09090b;
        }
        ::-webkit-scrollbar-thumb {
            background: #27272a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #f97316;
        }
    </style>
    {{ $head ?? '' }}
</head>
<body class="bg-[#09090b] text-zinc-100 antialiased selection:bg-orange-500 selection:text-white relative min-h-screen overflow-x-hidden flex flex-col justify-between">

    <!-- Ambient Grid & Glow Background -->
    <div class="fixed inset-0 grid-pattern pointer-events-none z-0 opacity-60"></div>
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] glow-sphere pointer-events-none z-0"></div>

    <!-- Floating Navigation Bar -->
    @include('partials.navbar')

    <!-- Main Page Content -->
    <main class="relative z-10 flex-grow pt-32 sm:pt-40">
        {{ $slot }}
    </main>

    <!-- Global Footer -->
    @include('partials.footer')

    {{ $scripts ?? '' }}
</body>
</html>
