<x-layouts.marketing>
    <!-- HERO SECTION -->
    <section class="relative px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto text-center pt-8 pb-12">
        <!-- Category Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-orange-500/30 bg-orange-500/10 text-orange-400 text-xs font-semibold uppercase tracking-wider mb-8 shadow-[0_0_15px_rgba(249,115,22,0.15)] animate-pulse">
            <span class="w-2 h-2 rounded-full bg-orange-400"></span>
            {{ __('marketing.hero_badge') }}
        </div>

        <!-- Hero Headline -->
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white max-w-4xl mx-auto leading-[1.12]">
            {{ __('marketing.hero_title_1') }} 
            <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-amber-400">
                {{ __('marketing.hero_title_2') }}
            </span>
        </h1>

        <!-- Subtitle -->
        <p class="mt-6 text-base sm:text-lg lg:text-xl text-zinc-400 max-w-2xl mx-auto font-normal leading-relaxed">
            {{ __('marketing.hero_subtitle') }}
        </p>

        <!-- CTA Buttons -->
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#live-demo" class="w-full sm:w-auto px-8 py-3.5 rounded-full text-sm sm:text-base font-bold text-white bg-gradient-to-r from-orange-500 via-orange-600 to-amber-600 hover:from-orange-400 hover:to-amber-500 shadow-[0_0_35px_rgba(249,115,22,0.45)] hover:shadow-[0_0_45px_rgba(249,115,22,0.7)] transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                </svg>
                {{ __('marketing.hero_cta_primary') }}
            </a>
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('pricing')) }}" class="w-full sm:w-auto px-8 py-3.5 rounded-full text-sm sm:text-base font-medium text-zinc-300 hover:text-white backdrop-blur-md bg-white/5 hover:bg-white/10 border border-white/10 transition-all duration-300 flex items-center justify-center gap-2">
                {{ __('marketing.hero_cta_secondary') }}
            </a>
        </div>

        <!-- Horizon Glow Arc Partial (Matching Reference Image) -->
        @include('partials.hero-arc')

        <!-- Social Proof Logos -->
        <div class="pt-6">
            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-500 mb-6">
                {{ __('marketing.metric_pages') }}: 48M+ | {{ __('marketing.metric_uptime') }}: 99.98%
            </p>
            <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-14 opacity-50 grayscale hover:grayscale-0 hover:opacity-90 transition-all duration-500">
                <span class="text-xl font-bold tracking-tight text-white flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded bg-zinc-400"></span> Supabase
                </span>
                <span class="text-xl font-bold tracking-tight text-white flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-zinc-400"></span> Spotify
                </span>
                <span class="text-xl font-bold tracking-tight text-white flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded bg-zinc-400"></span> Slack
                </span>
                <span class="text-xl font-bold tracking-tight text-white flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-lg bg-zinc-400"></span> Dropbox
                </span>
                <span class="text-xl font-bold tracking-tight text-white flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded bg-zinc-400"></span> Webflow
                </span>
            </div>
        </div>
    </section>

    <!-- CORE CAPABILITY PILLARS -->
    @include('partials.features')

    <!-- INTERACTIVE LIVE SCRAPER DEMO -->
    @include('partials.live-scraper')

    <!-- DECISION-MAKING METRICS & GRAPHIC (85% CARD) -->
    @include('partials.decision-metrics')

    <!-- PRICING TIERS -->
    @include('partials.pricing-cards')

    <!-- FAQ SECTION -->
    @include('partials.faq-section')

    <!-- FINAL CALL TO ACTION -->
    <section class="relative py-20 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto text-center border-t border-zinc-800/80 mt-12">
        <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">
            {{ __('marketing.features_title') }}
        </h2>
        <p class="mt-4 text-zinc-400 text-sm sm:text-base max-w-xl mx-auto">
            {{ __('marketing.features_subtitle') }}
        </p>
        <div class="mt-8 flex justify-center">
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('pricing')) }}" class="px-8 py-3.5 rounded-full text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-400 hover:to-amber-500 shadow-[0_0_30px_rgba(249,115,22,0.4)] transition-all transform hover:-translate-y-0.5">
                {{ __('marketing.start_free') }}
            </a>
        </div>
    </section>
</x-layouts.marketing>
