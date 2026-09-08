<x-layouts.marketing>
    <x-slot:title>Contact Us & Request Demo — Compitator AI</x-slot:title>
    <x-slot:description>Get in touch with our competitive intelligence team. Request an enterprise demo or discuss custom scraping solutions.</x-slot:description>

    <section class="relative px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            <!-- Left Info Column -->
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-orange-500/30 bg-orange-500/10 text-orange-400 text-xs font-semibold uppercase tracking-wider">
                    Get in Touch
                </div>
                <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight">
                    Let's discuss your <span class="text-orange-500">intelligence needs</span>
                </h1>
                <p class="text-sm sm:text-base text-zinc-400 leading-relaxed">
                    Have questions about scraping resilience, custom target sites, or high-volume enterprise SLAs? Our team is available 24/7.
                </p>

                <div class="pt-6 space-y-4 border-t border-zinc-800 text-xs text-zinc-300">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-500/10 border border-orange-500/30 text-orange-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-zinc-500">Direct Email</p>
                            <a href="mailto:intel@compitator.ai" class="text-white hover:text-orange-400 transition-colors font-medium">intel@compitator.ai</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-500/10 border border-orange-500/30 text-orange-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-zinc-500">Enterprise SLA Response</p>
                            <p class="text-white font-medium">&lt; 15 Minute Dedicated Escalation</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Contact Form Card -->
            <div class="p-8 rounded-3xl bg-zinc-950/80 border border-zinc-800 backdrop-blur-2xl shadow-2xl relative overflow-hidden">
                <form onsubmit="event.preventDefault(); alert('Thank you! Our competitive strategy specialist will contact you within 15 minutes.');" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">First Name</label>
                            <input type="text" required placeholder="Jane" class="w-full px-4 py-2.5 bg-zinc-900/80 border border-zinc-700/80 rounded-xl text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Last Name</label>
                            <input type="text" required placeholder="Doe" class="w-full px-4 py-2.5 bg-zinc-900/80 border border-zinc-700/80 rounded-xl text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Work Email</label>
                        <input type="email" required placeholder="jane@company.com" class="w-full px-4 py-2.5 bg-zinc-900/80 border border-zinc-700/80 rounded-xl text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Company Website</label>
                        <input type="text" required placeholder="https://company.com" class="w-full px-4 py-2.5 bg-zinc-900/80 border border-zinc-700/80 rounded-xl text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Competitor URLs to Monitor</label>
                        <input type="text" placeholder="e.g. competitor1.com, rival2.io" class="w-full px-4 py-2.5 bg-zinc-900/80 border border-zinc-700/80 rounded-xl text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Message / Custom Requirements</label>
                        <textarea rows="4" placeholder="Tell us about the data points or cadence you need..." class="w-full px-4 py-2.5 bg-zinc-900/80 border border-zinc-700/80 rounded-xl text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 transition-colors"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-400 hover:to-amber-500 shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all">
                        Request Strategy Demo
                    </button>
                </form>
            </div>

        </div>
    </section>
</x-layouts.marketing>
