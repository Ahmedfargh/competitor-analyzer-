<x-layouts.marketing>
    <x-slot:title>Pricing Plans & Feature Matrix — Compitator AI</x-slot:title>
    <x-slot:description>Choose the right plan for your competitive intelligence needs. Scalable autonomous scrapers from early-stage startups to Fortune 500 teams.</x-slot:description>

    <!-- Pricing Cards Partial -->
    @include('partials.pricing-cards')

    <!-- Detailed Feature Comparison Table -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
        <div class="text-center mb-12">
            <h3 class="text-2xl font-bold text-white">Detailed Plan Comparison</h3>
            <p class="text-xs text-zinc-400 mt-1">Explore every feature and technical limit included in each plan.</p>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-zinc-800 bg-zinc-950/60 backdrop-blur-xl">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="border-b border-zinc-800 bg-zinc-900/60 text-white font-semibold">
                    <tr>
                        <th class="py-4 px-6">Feature</th>
                        <th class="py-4 px-4 text-center">Starter</th>
                        <th class="py-4 px-4 text-center text-orange-400">Growth & Scale</th>
                        <th class="py-4 px-4 text-center">Enterprise</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60">
                    <tr>
                        <td class="py-3.5 px-6 font-medium text-white">Tracked Competitors</td>
                        <td class="py-3.5 px-4 text-center text-zinc-400">5</td>
                        <td class="py-3.5 px-4 text-center text-white font-semibold">25</td>
                        <td class="py-3.5 px-4 text-center text-orange-400 font-bold">Unlimited</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-6 font-medium text-white">Scraping Cadence</td>
                        <td class="py-3.5 px-4 text-center text-zinc-400">Daily</td>
                        <td class="py-3.5 px-4 text-center text-white font-semibold">Hourly</td>
                        <td class="py-3.5 px-4 text-center text-orange-400 font-bold">Sub-15 Min Real-Time</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-6 font-medium text-white">Stealth Proxy Fleet</td>
                        <td class="py-3.5 px-4 text-center text-zinc-400">Shared Data Center</td>
                        <td class="py-3.5 px-4 text-center text-white font-semibold">Rotating Residential</td>
                        <td class="py-3.5 px-4 text-center text-orange-400 font-bold">Dedicated Mobile & Residential</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-6 font-medium text-white">AI Change Summaries</td>
                        <td class="py-3.5 px-4 text-center text-zinc-400">Basic Text</td>
                        <td class="py-3.5 px-4 text-center text-white font-semibold">Semantic Battlecards</td>
                        <td class="py-3.5 px-4 text-center text-orange-400 font-bold">Custom LLM Synthesis</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-6 font-medium text-white">Webhook & Slack Alerts</td>
                        <td class="py-3.5 px-4 text-center text-zinc-500">—</td>
                        <td class="py-3.5 px-4 text-center text-emerald-400 font-bold">✓ Included</td>
                        <td class="py-3.5 px-4 text-center text-emerald-400 font-bold">✓ Included</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-6 font-medium text-white">Historical Snapshot Retention</td>
                        <td class="py-3.5 px-4 text-center text-zinc-400">30 Days</td>
                        <td class="py-3.5 px-4 text-center text-white font-semibold">365 Days</td>
                        <td class="py-3.5 px-4 text-center text-orange-400 font-bold">Unlimited</td>
                    </tr>
                    <tr>
                        <td class="py-3.5 px-6 font-medium text-white">Support SLA</td>
                        <td class="py-3.5 px-4 text-center text-zinc-400">Standard Email</td>
                        <td class="py-3.5 px-4 text-center text-white font-semibold">Priority 24h</td>
                        <td class="py-3.5 px-4 text-center text-orange-400 font-bold">Dedicated Slack Channel</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- FAQ Partial -->
    @include('partials.faq-section')
</x-layouts.marketing>
