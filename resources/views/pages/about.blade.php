<x-layouts.marketing>
    <x-slot:title>About Us — Compitator AI & The Future of Market Intelligence</x-slot:title>
    <x-slot:description>Learn about Compitator AI's mission to give high-growth teams an unfair strategic advantage through autonomous web scraping and AI intelligence.</x-slot:description>

    <!-- Header Hero -->
    <section class="relative px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto text-center pt-8 pb-16">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-orange-500/30 bg-orange-500/10 text-orange-400 text-xs font-semibold uppercase tracking-wider mb-6">
            Our Story & Vision
        </div>
        <h1 class="text-4xl sm:text-6xl font-extrabold text-white tracking-tight leading-tight">
            We Built the Eyes and Brains for 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-amber-400">
                Modern Market Strategy
            </span>
        </h1>
        <p class="mt-6 text-base sm:text-lg text-zinc-400 max-w-2xl mx-auto leading-relaxed">
            In hyper-competitive software markets, product changes happen overnight. Compitator AI transforms messy website updates into crystal-clear strategic battlecards in real-time.
        </p>
    </section>

    <!-- Stats Bar -->
    <section class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto mb-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 p-8 rounded-3xl bg-zinc-950/80 border border-zinc-800/80 backdrop-blur-2xl shadow-xl text-center">
            <div>
                <p class="text-3xl sm:text-4xl font-extrabold text-white">10M+</p>
                <p class="text-xs text-zinc-400 mt-1 uppercase tracking-wider">Pages Scraped Daily</p>
            </div>
            <div>
                <p class="text-3xl sm:text-4xl font-extrabold text-orange-400">99.8%</p>
                <p class="text-xs text-zinc-400 mt-1 uppercase tracking-wider">Stealth Uptime</p>
            </div>
            <div>
                <p class="text-3xl sm:text-4xl font-extrabold text-white">&lt; 3min</p>
                <p class="text-xs text-zinc-400 mt-1 uppercase tracking-wider">Alert Latency</p>
            </div>
            <div>
                <p class="text-3xl sm:text-4xl font-extrabold text-orange-400">450+</p>
                <p class="text-xs text-zinc-400 mt-1 uppercase tracking-wider">SaaS Companies</p>
            </div>
        </div>
    </section>

    <!-- Mission & Core Principles -->
    <section id="mission" class="py-16 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-xs font-semibold tracking-wider text-orange-400 uppercase">The Problem We Solve</span>
                <h2 class="text-3xl font-extrabold text-white mt-2 leading-tight">
                    Manual competitor monitoring is broken, slow, and full of noise.
                </h2>
                <p class="mt-4 text-sm text-zinc-400 leading-relaxed">
                    Product managers, marketers, and sales leaders waste countless hours clicking through rival websites, copying pricing tables into spreadsheets, and reading blog announcements days after they launch.
                </p>
                <p class="mt-3 text-sm text-zinc-400 leading-relaxed">
                    By the time you discover your competitor lowered their annual plan by 20% or launched your flagship feature, you have already lost deals. Compitator changes that dynamic completely.
                </p>
            </div>

            <div class="space-y-4">
                <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        Autonomous Stealth Crawling
                    </h3>
                    <p class="text-xs text-zinc-400 mt-1.5 leading-relaxed">
                        Our distributed residential proxy fleet bypasses enterprise bot protection effortlessly, capturing pristine full-page snapshots without getting blocked.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        Cognitive Semantic Intelligence
                    </h3>
                    <p class="text-xs text-zinc-400 mt-1.5 leading-relaxed">
                        Rather than raw character diffs, our fine-tuned LLMs understand context: separating marketing fluff from critical pricing and feature pivots.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-zinc-900/40 border border-zinc-800">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        Ethical Public Data Standards
                    </h3>
                    <p class="text-xs text-zinc-400 mt-1.5 leading-relaxed">
                        We only scrape publicly accessible data and maintain strict politeness policies, ensuring zero server strain on target domains.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership / Philosophy -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto border-t border-zinc-800/60 mt-12 text-center">
        <h2 class="text-3xl font-extrabold text-white tracking-tight">Built by Engineers, Designed for Strategists</h2>
        <p class="mt-4 text-sm text-zinc-400 max-w-xl mx-auto">
            Headquartered globally with team members distributed across San Francisco, London, and Tokyo. We are dedicated to providing the most reliable competitive intelligence infrastructure on Earth.
        </p>
        <div class="mt-8">
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-xs font-bold text-white bg-orange-500 hover:bg-orange-600 shadow-[0_0_20px_rgba(249,115,22,0.35)] transition-all">
                Get in Touch with Us
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </section>
</x-layouts.marketing>
