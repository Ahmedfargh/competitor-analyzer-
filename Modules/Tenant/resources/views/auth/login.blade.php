<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tenant->company_name ?? __('tenant.login_title') }} — {{ __('tenant.login_title') }}</title>

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
<body class="bg-[#09090b] text-zinc-100 min-h-screen flex items-center justify-center p-4 selection:bg-orange-500 selection:text-white relative overflow-hidden">

    <!-- Background Horizon Arc & Ambient Lighting -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[720px] h-[500px] bg-gradient-to-b from-orange-500/20 via-amber-500/10 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 right-0 h-48 bg-gradient-to-t from-orange-500/5 to-transparent"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-orange-500 to-amber-500 shadow-[0_0_30px_rgba(249,115,22,0.4)] mb-4 border border-orange-400/30">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                {{ $tenant->company_name ?? __('tenant.login_title') }}
            </h1>
            <p class="text-xs sm:text-sm text-zinc-400 mt-2 max-w-sm mx-auto leading-relaxed">
                {{ __('tenant.login_subtitle') }}
            </p>
            <div class="mt-2.5 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-zinc-900/80 border border-white/10 text-[11px] font-mono text-orange-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>{{ $tenant->id }}</span>
            </div>
        </div>

        <!-- Login Card -->
        <div class="p-8 sm:p-9 rounded-3xl bg-zinc-900/85 backdrop-blur-2xl border border-white/10 shadow-2xl shadow-black/80 relative">
            <div class="absolute -top-px left-8 right-8 h-px bg-gradient-to-r from-transparent via-orange-500/60 to-transparent"></div>

            @if (isset($errors) && $errors->any())
                <div class="mb-6 p-3.5 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-300 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('tenant.login.submit', [], false) }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-2">
                        {{ __('tenant.email_address') }}
                    </label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="{{ __('tenant.email_placeholder') }}"
                               class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all text-sm font-sans">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-zinc-400">
                            {{ __('tenant.password') }}
                        </label>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               placeholder="{{ __('tenant.password_placeholder') }}"
                               class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all text-sm font-sans">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-zinc-400 hover:text-zinc-200 transition-colors select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-zinc-950 border-white/10 text-orange-500 focus:ring-orange-500/20">
                        <span>{{ __('tenant.remember_me') }}</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 shadow-[0_0_25px_rgba(249,115,22,0.4)] transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                    <span>{{ __('tenant.sign_in') }}</span>
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Language Switcher & Host Badge -->
        <div class="mt-6 flex items-center justify-between text-xs text-zinc-500 px-2">
            <span class="text-[11px] text-zinc-500">
                {{ __('tenant.isolated_partition') }} <span class="font-mono text-zinc-400">{{ request()->getHost() }}</span>
            </span>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('tenant.locale', ['locale' => 'en'], false) }}"
                   class="px-2.5 py-1 rounded-lg transition-all {{ app()->getLocale() === 'en' ? 'bg-orange-500/10 text-orange-400 font-bold border border-orange-500/20' : 'hover:text-zinc-300 text-zinc-500' }}">
                    EN
                </a>
                <span class="text-zinc-700">|</span>
                <a href="{{ route('tenant.locale', ['locale' => 'ar'], false) }}"
                   class="px-2.5 py-1 rounded-lg transition-all {{ app()->getLocale() === 'ar' ? 'bg-orange-500/10 text-orange-400 font-bold border border-orange-500/20' : 'hover:text-zinc-300 text-zinc-500' }}">
                    العربية
                </a>
            </div>
        </div>
    </div>

</body>
</html>
