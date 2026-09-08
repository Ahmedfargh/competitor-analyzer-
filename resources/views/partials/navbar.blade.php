<header class="fixed top-5 inset-x-0 z-50 px-4 sm:px-6" x-data="{ mobileOpen: false }">
    <nav class="max-w-5xl mx-auto backdrop-blur-xl bg-zinc-950/70 border border-white/10 rounded-full px-5 py-3 flex items-center justify-between shadow-[0_10px_35px_rgba(0,0,0,0.6)] transition-all duration-300">
        <!-- Brand Logo -->
        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('home')) }}" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white shadow-[0_0_20px_rgba(249,115,22,0.4)] group-hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-base tracking-tight text-white flex items-center gap-1.5">
                    {{ __('marketing.brand_name') }} <span class="text-[10px] px-1.5 py-0.5 rounded bg-orange-500/20 text-orange-400 font-semibold border border-orange-500/30">{{ __('marketing.brand_ai') }}</span>
                </span>
            </div>
        </a>

        <!-- Desktop Nav Links -->
        <div class="hidden md:flex items-center gap-7 text-sm font-medium">
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('home')) }}" class="{{ request()->routeIs('home') ? 'text-orange-400 font-semibold' : 'text-zinc-400 hover:text-white' }} transition-colors">
                {{ __('marketing.nav_home') }}
            </a>
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('features')) }}" class="{{ request()->routeIs('features') ? 'text-orange-400 font-semibold' : 'text-zinc-400 hover:text-white' }} transition-colors">
                {{ __('marketing.nav_features') }}
            </a>
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('pricing')) }}" class="{{ request()->routeIs('pricing') ? 'text-orange-400 font-semibold' : 'text-zinc-400 hover:text-white' }} transition-colors">
                {{ __('marketing.nav_pricing') }}
            </a>
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('about')) }}" class="{{ request()->routeIs('about') ? 'text-orange-400 font-semibold' : 'text-zinc-400 hover:text-white' }} transition-colors">
                {{ __('marketing.nav_about') }}
            </a>
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('contact')) }}" class="{{ request()->routeIs('contact') ? 'text-orange-400 font-semibold' : 'text-zinc-400 hover:text-white' }} transition-colors">
                {{ __('marketing.nav_contact') }}
            </a>
        </div>

        <!-- Desktop Auth / CTA Actions & Language Switcher -->
        <div class="hidden sm:flex items-center gap-3">
            <!-- Language Selector Toggle -->
            <div class="flex items-center bg-zinc-900/90 border border-white/10 rounded-full p-1 text-xs shadow-inner">
                @foreach(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                       class="px-2.5 py-1 rounded-full transition-all duration-200 {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === $localeCode ? 'bg-orange-500 text-white font-semibold shadow-[0_0_10px_rgba(249,115,22,0.4)]' : 'text-zinc-400 hover:text-white' }}"
                       title="{{ $properties['native'] }} ({{ $properties['name'] }})">
                        {{ strtoupper($localeCode) }}
                    </a>
                @endforeach
            </div>

            <a href="/login" class="text-xs sm:text-sm font-medium text-zinc-300 hover:text-white px-2 py-1.5 transition-colors">
                {{ __('marketing.sign_in') }}
            </a>
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('pricing')) }}" class="inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500 rounded-full shadow-[0_0_20px_rgba(249,115,22,0.35)] hover:shadow-[0_0_25px_rgba(249,115,22,0.6)] transition-all duration-300 transform hover:-translate-y-0.5">
                {{ __('marketing.start_free') }}
                <svg class="w-3.5 h-3.5 rtl:rotate-180 ltr:ml-1.5 rtl:mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <div class="flex sm:hidden items-center gap-2">
            <!-- Compact Mobile Language Toggle -->
            <div class="flex items-center bg-zinc-900 border border-white/10 rounded-full p-0.5 text-[11px]">
                @foreach(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                       class="px-2 py-0.5 rounded-full {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === $localeCode ? 'bg-orange-500 text-white font-bold' : 'text-zinc-400' }}">
                        {{ strtoupper($localeCode) }}
                    </a>
                @endforeach
            </div>

            <button onclick="toggleMobileNav()" type="button" class="p-2 text-zinc-400 hover:text-white rounded-lg focus:outline-none" aria-label="Toggle navigation">
                <svg id="navOpenIcon" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="navCloseIcon" class="w-6 h-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Dropdown Menu -->
    <div id="mobileMenu" class="hidden sm:hidden max-w-5xl mx-auto mt-2 backdrop-blur-2xl bg-zinc-950/95 border border-white/10 rounded-2xl p-5 shadow-2xl space-y-3">
        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('home')) }}" class="block text-sm py-2 {{ request()->routeIs('home') ? 'text-orange-400 font-semibold' : 'text-zinc-300 hover:text-white' }}">{{ __('marketing.nav_home') }}</a>
        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('features')) }}" class="block text-sm py-2 {{ request()->routeIs('features') ? 'text-orange-400 font-semibold' : 'text-zinc-300 hover:text-white' }}">{{ __('marketing.nav_features') }}</a>
        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('pricing')) }}" class="block text-sm py-2 {{ request()->routeIs('pricing') ? 'text-orange-400 font-semibold' : 'text-zinc-300 hover:text-white' }}">{{ __('marketing.nav_pricing') }}</a>
        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('about')) }}" class="block text-sm py-2 {{ request()->routeIs('about') ? 'text-orange-400 font-semibold' : 'text-zinc-300 hover:text-white' }}">{{ __('marketing.nav_about') }}</a>
        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('contact')) }}" class="block text-sm py-2 {{ request()->routeIs('contact') ? 'text-orange-400 font-semibold' : 'text-zinc-300 hover:text-white' }}">{{ __('marketing.nav_contact') }}</a>
        <div class="pt-3 border-t border-zinc-800 flex items-center justify-between">
            <a href="/login" class="text-sm text-zinc-300">{{ __('marketing.sign_in') }}</a>
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('pricing')) }}" class="px-4 py-2 rounded-full text-xs font-bold text-white bg-orange-500">{{ __('marketing.start_free') }}</a>
        </div>
    </div>
</header>

<script>
    function toggleMobileNav() {
        const menu = document.getElementById('mobileMenu');
        const openIcon = document.getElementById('navOpenIcon');
        const closeIcon = document.getElementById('navCloseIcon');
        
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            openIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
            openIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    }
</script>
