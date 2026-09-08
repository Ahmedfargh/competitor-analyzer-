<!DOCTYPE html>
<html lang="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() }}" dir="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocaleDirection() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('admin.title') }} — Compitator AI</title>

    <!-- Google Fonts: Cairo for Arabic RTL & Plus Jakarta Sans for English -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

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
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>

    @livewireStyles
</head>
<body class="bg-[#09090b] text-zinc-100 min-h-screen flex flex-col antialiased selection:bg-orange-500 selection:text-white bg-grid-cyber relative overflow-x-hidden">

    <!-- Top Horizon Arc & Ambient Neon Radiance -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <!-- Top Horizon Accent Line -->
        <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-orange-500/60 to-transparent"></div>
        <div class="absolute -top-32 left-1/3 w-[600px] h-[350px] bg-gradient-to-b from-orange-500/15 via-amber-500/5 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 -right-40 w-96 h-96 bg-orange-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-80 h-80 bg-amber-600/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 flex min-h-screen">
        <!-- LUXURY DARK SIDEBAR -->
        <aside class="w-68 bg-zinc-950/85 backdrop-blur-2xl border-e border-white/[0.08] flex flex-col justify-between hidden md:flex shrink-0 shadow-2xl z-20">
            <div>
                <!-- Brand Header -->
                <div class="h-20 flex items-center px-6 border-b border-white/[0.08] gap-3.5 relative">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-orange-500 via-orange-600 to-amber-500 flex items-center justify-center shadow-[0_0_25px_rgba(249,115,22,0.45)] ring-1 ring-orange-400/50 shrink-0">
                        <svg class="w-5 h-5 text-white shrink-0" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="overflow-hidden">
                        <span class="text-sm font-black tracking-tight text-white block">COMPITATOR</span>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-orange-400 font-mono">ADMIN HUB</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5 text-xs font-semibold">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                       class="relative flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-orange-500/20 via-orange-500/10 to-transparent text-orange-400 border border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.15)] font-bold' : 'text-zinc-400 hover:text-white hover:bg-white/[0.04]' }}">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-zinc-900 text-zinc-400' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </div>
                        <span>{{ __('admin.dashboard') }}</span>
                    </a>

                    <!-- Multi-Tenants -->
                    <a href="{{ route('admin.tenants.index') }}"
                       class="relative flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.tenants.*') ? 'bg-gradient-to-r from-orange-500/20 via-orange-500/10 to-transparent text-orange-400 border border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.15)] font-bold' : 'text-zinc-400 hover:text-white hover:bg-white/[0.04]' }}">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.tenants.*') ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-zinc-900 text-zinc-400' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span>{{ __('admin.tenants') }}</span>
                    </a>

                    <!-- Subscription Plans (EGP) -->
                    <a href="{{ route('admin.plans.index') }}"
                       class="relative flex items-center justify-between px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.plans.*') ? 'bg-gradient-to-r from-orange-500/20 via-orange-500/10 to-transparent text-orange-400 border border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.15)] font-bold' : 'text-zinc-400 hover:text-white hover:bg-white/[0.04]' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.plans.*') ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-zinc-900 text-zinc-400' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <span>{{ __('admin.plans') }}</span>
                        </div>
                        <span class="text-[9px] px-2 py-0.5 rounded-full bg-orange-500/20 border border-orange-500/30 text-orange-300 font-mono font-bold uppercase tracking-wider">
                            EGP
                        </span>
                    </a>

                    <!-- Landing Pages & Posts (Gutenberg) -->
                    <a href="{{ route('admin.posts.index') }}"
                       class="relative flex items-center justify-between px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.posts.*') ? 'bg-gradient-to-r from-orange-500/20 via-orange-500/10 to-transparent text-orange-400 border border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.15)] font-bold' : 'text-zinc-400 hover:text-white hover:bg-white/[0.04]' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.posts.*') ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-zinc-900 text-zinc-400' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                            <span>{{ __('admin.content_management') ?? 'Landing & Posts' }}</span>
                        </div>
                        <span class="text-[9px] px-2 py-0.5 rounded-full bg-orange-500/20 border border-orange-500/30 text-orange-300 font-mono font-bold uppercase tracking-wider">
                            Blocks
                        </span>
                    </a>

                    <!-- Activity Logs -->
                    <a href="{{ route('admin.activity-logs.index') }}"
                       class="relative flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.activity-logs.*') ? 'bg-gradient-to-r from-orange-500/20 via-orange-500/10 to-transparent text-orange-400 border border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.15)] font-bold' : 'text-zinc-400 hover:text-white hover:bg-white/[0.04]' }}">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.activity-logs.*') ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-zinc-900 text-zinc-400' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span>{{ __('admin.activity_logs') }}</span>
                    </a>

                    <!-- Section: Access Control -->
                    <div class="pt-3 pb-1">
                        <span class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-zinc-500 font-mono">{{ __('admin.access_control') }}</span>
                    </div>

                    <!-- System Admin Users -->
                    <a href="{{ route('admin.users.index') }}"
                       class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-orange-500/20 via-orange-500/10 to-transparent text-orange-400 border border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.15)] font-bold' : 'text-zinc-400 hover:text-white hover:bg-white/[0.04]' }}">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.users.*') ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-zinc-900 text-zinc-400' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span>{{ __('admin.system_admins') }}</span>
                    </a>

                    <!-- Roles -->
                    <a href="{{ route('admin.roles.index') }}"
                       class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.roles.*') ? 'bg-gradient-to-r from-orange-500/20 via-orange-500/10 to-transparent text-orange-400 border border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.15)] font-bold' : 'text-zinc-400 hover:text-white hover:bg-white/[0.04]' }}">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.roles.*') ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-zinc-900 text-zinc-400' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span>{{ __('admin.roles') }}</span>
                    </a>

                    <!-- Permissions -->
                    <a href="{{ route('admin.permissions.index') }}"
                       class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.permissions.*') ? 'bg-gradient-to-r from-orange-500/20 via-orange-500/10 to-transparent text-orange-400 border border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.15)] font-bold' : 'text-zinc-400 hover:text-white hover:bg-white/[0.04]' }}">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.permissions.*') ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-zinc-900 text-zinc-400' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <span>{{ __('admin.permissions') }}</span>
                    </a>

                    <!-- Divider -->
                    <div class="pt-4 mt-4 border-t border-white/[0.06]">
                        <a href="{{ route('home') }}" target="_blank"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs text-zinc-400 hover:text-white hover:bg-white/[0.04] transition-all group">
                            <div class="w-6 h-6 rounded-lg bg-zinc-900 flex items-center justify-center text-zinc-500 group-hover:text-orange-400 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </div>
                            <span>{{ __('marketing.nav_home') }}</span>
                        </a>
                    </div>
                </nav>
            </div>

            <!-- Admin Profile Bottom Card -->
            <div class="p-4 border-t border-white/[0.08]">
                <div class="p-3 rounded-2xl bg-zinc-900/80 border border-white/[0.08] flex items-center justify-between relative shadow-lg">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="relative shrink-0">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-orange-500 to-amber-500 text-white flex items-center justify-center font-bold text-xs shadow-md">
                                {{ substr(auth('admin')->user()->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-zinc-900"></span>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-white truncate">{{ auth('admin')->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[10px] text-zinc-400 truncate font-mono">{{ auth('admin')->user()->email ?? 'admin@compitator.com' }}</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" title="{{ __('admin.logout') }}"
                                class="p-2 rounded-xl text-zinc-400 hover:text-red-400 hover:bg-red-500/10 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN VIEW WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- TOP NAVBAR -->
            <header class="h-20 bg-zinc-950/60 backdrop-blur-2xl border-b border-white/[0.08] px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <!-- Page Title & Operational Status -->
                <div class="flex items-center gap-4">
                    <h2 class="text-base sm:text-lg font-black text-white tracking-tight flex items-center gap-2">
                        <span>{{ $header ?? __('admin.dashboard') }}</span>
                    </h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 shadow-[0_0_12px_rgba(16,185,129,0.2)]">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>{{ __('admin.system_healthy') }}</span>
                    </span>
                </div>

                <!-- Right Controls: Language Switcher & Mobile Logout -->
                <div class="flex items-center gap-3">
                    <!-- Language Switcher Pill -->
                    <div class="flex items-center bg-zinc-900/90 border border-white/10 rounded-2xl p-1 text-xs font-bold shadow-inner">
                        @foreach (\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                               class="px-3 py-1.5 rounded-xl transition-all duration-200 {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === $localeCode ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.4)]' : 'text-zinc-400 hover:text-white' }}">
                                {{ strtoupper($localeCode) }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Mobile Logout Button -->
                    <form action="{{ route('admin.logout') }}" method="POST" class="md:hidden">
                        @csrf
                        <button type="submit" class="p-2.5 rounded-xl bg-zinc-900 border border-white/10 text-zinc-300 hover:text-red-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Alerts Banner -->
            <main class="flex-1 p-6 sm:p-8 max-w-7xl w-full mx-auto">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 text-sm flex items-center gap-3 shadow-lg shadow-emerald-950/30 animate-fade-in">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/25 text-red-300 text-sm space-y-2 shadow-lg shadow-red-950/30">
                        <div class="flex items-center gap-2.5 font-bold">
                            <svg class="w-5 h-5 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>{{ __('Please correct the errors below:') }}</span>
                        </div>
                        <ul class="list-disc list-inside ps-5 text-xs text-red-300/90 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
