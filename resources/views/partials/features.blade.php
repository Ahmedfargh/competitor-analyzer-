<section id="features" class="py-24 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto relative">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="inline-block px-3 py-1 text-xs font-semibold tracking-wider text-orange-400 uppercase rounded-full bg-orange-500/10 border border-orange-500/20 mb-3">
            {{ __('marketing.features_badge') }}
        </span>
        <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">
            {{ __('marketing.features_title') }}
        </h2>
        <p class="mt-4 text-zinc-400 text-base sm:text-lg">
            {{ __('marketing.features_subtitle') }}
        </p>
    </div>

    <!-- 3 Column Feature Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Card 1 -->
        <div class="p-8 rounded-2xl bg-zinc-900/40 border border-zinc-800/70 hover:border-orange-500/40 backdrop-blur-xl transition-all duration-300 hover:shadow-[0_10px_30px_rgba(249,115,22,0.1)] group">
            <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">
                {{ __('marketing.feature_1_title') }}
            </h3>
            <p class="text-sm text-zinc-400 leading-relaxed mb-4">
                {{ __('marketing.feature_1_desc') }}
            </p>
            <div class="flex items-center text-xs font-semibold text-orange-400">
                <span>DOM Snapshotting & Diffing</span>
                <svg class="w-3.5 h-3.5 rtl:rotate-180 ltr:ml-1 rtl:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="p-8 rounded-2xl bg-zinc-900/40 border border-zinc-800/70 hover:border-orange-500/40 backdrop-blur-xl transition-all duration-300 hover:shadow-[0_10px_30px_rgba(249,115,22,0.1)] group">
            <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">
                {{ __('marketing.feature_2_title') }}
            </h3>
            <p class="text-sm text-zinc-400 leading-relaxed mb-4">
                {{ __('marketing.feature_2_desc') }}
            </p>
            <div class="flex items-center text-xs font-semibold text-orange-400">
                <span>Real-Time Elasticity Curve</span>
                <svg class="w-3.5 h-3.5 rtl:rotate-180 ltr:ml-1 rtl:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="p-8 rounded-2xl bg-zinc-900/40 border border-zinc-800/70 hover:border-orange-500/40 backdrop-blur-xl transition-all duration-300 hover:shadow-[0_10px_30px_rgba(249,115,22,0.1)] group">
            <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">
                {{ __('marketing.feature_3_title') }}
            </h3>
            <p class="text-sm text-zinc-400 leading-relaxed mb-4">
                {{ __('marketing.feature_3_desc') }}
            </p>
            <div class="flex items-center text-xs font-semibold text-orange-400">
                <span>Automated Sales Battlecards</span>
                <svg class="w-3.5 h-3.5 rtl:rotate-180 ltr:ml-1 rtl:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>

    </div>
</section>
