<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tenant->company_name ?? __('tenant.dashboard_title') }} — {{ __('tenant.dashboard_title') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color-scheme: dark;
        }
        [dir="rtl"] {
            font-family: 'Cairo', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="bg-[#09090b] text-zinc-100 min-h-screen p-4 sm:p-8 selection:bg-orange-500 selection:text-white relative overflow-x-hidden">

    <!-- Ambient Lighting Background -->
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-gradient-to-b from-orange-500/15 via-amber-500/5 to-transparent rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto space-y-8">
        <!-- Top Navbar -->
        <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 sm:p-6 rounded-3xl bg-zinc-900/80 border border-white/10 backdrop-blur-2xl shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-orange-500 to-amber-500 flex items-center justify-center font-black text-white shadow-lg shadow-orange-500/25 border border-orange-400/30">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white tracking-tight">{{ $tenant->company_name ?? __('tenant.dashboard_title') }}</h1>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[11px] font-mono text-orange-400">{{ $tenant->id }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 self-end sm:self-center">
                <!-- Language Switcher -->
                <div class="flex items-center gap-1 p-1 rounded-xl bg-zinc-950/80 border border-white/10 text-xs">
                    <a href="{{ route('tenant.locale', ['locale' => 'en'], false) }}"
                       class="px-2.5 py-1 rounded-lg transition-all {{ app()->getLocale() === 'en' ? 'bg-orange-500/15 text-orange-400 font-bold' : 'text-zinc-400 hover:text-white' }}">
                        EN
                    </a>
                    <a href="{{ route('tenant.locale', ['locale' => 'ar'], false) }}"
                       class="px-2.5 py-1 rounded-lg transition-all {{ app()->getLocale() === 'ar' ? 'bg-orange-500/15 text-orange-400 font-bold' : 'text-zinc-400 hover:text-white' }}">
                        العربية
                    </a>
                </div>

                <!-- User Profile -->
                <div class="text-end hidden md:block px-2">
                    <p class="text-xs font-bold text-white">{{ $user->name }}</p>
                    <p class="text-[11px] text-zinc-400 font-mono">{{ $user->email }}</p>
                </div>

                <!-- Sign Out -->
                <form action="{{ route('tenant.logout', [], false) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-zinc-800/80 hover:bg-red-500/10 text-zinc-300 hover:text-red-400 text-xs font-semibold border border-white/10 hover:border-red-500/20 transition-all">
                        {{ __('tenant.sign_out') }}
                    </button>
                </form>
            </div>
        </header>

        <!-- Welcome Hero Banner -->
        <div class="p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-zinc-900/90 via-zinc-900/60 to-zinc-950/90 border border-white/10 shadow-2xl backdrop-blur-2xl relative overflow-hidden">
            <div class="absolute -top-px left-12 right-12 h-px bg-gradient-to-r from-transparent via-orange-500/50 to-transparent"></div>
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold mb-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    {{ __('tenant.context_active') }}
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    {{ __('tenant.welcome_back') }}, {{ $user->name }}!
                </h2>
                <p class="text-xs sm:text-sm text-zinc-400 mt-2.5 leading-relaxed">
                    {{ __('tenant.welcome_message') }}
                </p>
            </div>
        </div>

        <!-- Metric & Partition Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Database Connection -->
            <div class="p-6 rounded-3xl bg-zinc-900/80 backdrop-blur-xl border border-white/10 shadow-xl space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">{{ __('tenant.database_connection') }}</span>
                <p class="text-lg font-black text-white font-mono">{{ config('database.default') }}</p>
                <p class="text-xs text-zinc-500">{{ __('tenant.database_desc') }}</p>
            </div>

            <!-- Authentication Guard -->
            <div class="p-6 rounded-3xl bg-zinc-900/80 backdrop-blur-xl border border-white/10 shadow-xl space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">{{ __('tenant.auth_guard') }}</span>
                <p class="text-lg font-black text-orange-400 font-mono">auth:tenant</p>
                <p class="text-xs text-zinc-500">{{ __('tenant.auth_guard_desc') }}</p>
            </div>

            <!-- Domain Host -->
            <div class="p-6 rounded-3xl bg-zinc-900/80 backdrop-blur-xl border border-white/10 shadow-xl space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">{{ __('tenant.domain_host') }}</span>
                <p class="text-lg font-black text-emerald-400 font-mono break-all">{{ request()->getHost() }}</p>
                <p class="text-xs text-zinc-500">{{ __('tenant.domain_desc') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
