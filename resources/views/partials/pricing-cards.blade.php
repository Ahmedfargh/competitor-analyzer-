<section id="pricing" class="py-24 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto relative">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-xs font-semibold tracking-wider text-orange-400 uppercase">{{ __('marketing.pricing_badge') }}</span>
        <h2 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight mt-2">
            {{ __('marketing.pricing_title') }}
        </h2>
        <p class="mt-4 text-zinc-400 text-base">
            {{ __('marketing.pricing_subtitle') }}
        </p>

        <!-- Currency & Billing Controls -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <!-- Currency Toggle -->
            <div class="inline-flex items-center p-1 rounded-full bg-zinc-900 border border-zinc-800">
                <button type="button" id="curEgpBtn" onclick="setCurrency('egp')"
                        class="px-4 py-1.5 rounded-full text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 shadow-md transition-all">
                    EGP (ج.م)
                </button>
                <button type="button" id="curUsdBtn" onclick="setCurrency('usd')"
                        class="px-4 py-1.5 rounded-full text-xs font-bold text-zinc-400 hover:text-white transition-all">
                    USD ($)
                </button>
            </div>

            <!-- Billing Toggle -->
            <div class="inline-flex items-center p-1 rounded-full bg-zinc-900 border border-zinc-800">
                <button type="button" id="monthlyBtn" onclick="setBilling('monthly')"
                        class="px-5 py-1.5 rounded-full text-xs font-semibold text-white bg-zinc-800 shadow-md transition-all">
                    {{ __('marketing.billing_monthly') }}
                </button>
                <button type="button" id="annualBtn" onclick="setBilling('annual')"
                        class="px-5 py-1.5 rounded-full text-xs font-semibold text-zinc-400 hover:text-white transition-all">
                    {{ __('marketing.billing_annual') }}
                </button>
            </div>
        </div>
    </div>

    <!-- 3 Pricing Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
        
        <!-- Plan 1: Starter -->
        <div class="p-8 rounded-3xl bg-zinc-900/40 border border-zinc-800 backdrop-blur-xl flex flex-col justify-between hover:border-zinc-700 transition-all">
            <div>
                <h3 class="text-xl font-bold text-white">Starter / خطة البداية</h3>
                <p class="text-xs text-zinc-400 mt-1">Essential competitor tracking for early startups.</p>
                <div class="mt-6 flex items-baseline gap-1">
                    <span class="text-4xl font-extrabold text-white font-mono price-starter">1,490</span>
                    <span class="text-xs text-orange-400 font-bold currency-unit">ج.م</span>
                    <span class="text-xs text-zinc-500">/ {{ __('marketing.billing_monthly') }}</span>
                </div>

                <ul class="mt-8 space-y-3.5 text-xs text-zinc-300">
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        10 Tracked Competitor Profiles
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Daily Automated Scrapes
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Gemini 2.5 Flash Market Summaries
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Arabic & English Reports
                    </li>
                </ul>
            </div>

            <div class="mt-8">
                <a href="#live-demo" class="w-full block py-3 rounded-full text-center text-xs font-bold text-white bg-zinc-800 hover:bg-zinc-700 transition-colors">
                    {{ __('marketing.hero_cta_primary') }}
                </a>
            </div>
        </div>

        <!-- Plan 2: Pro (Featured) -->
        <div class="p-8 rounded-3xl bg-zinc-950/90 border-2 border-orange-500 backdrop-blur-2xl flex flex-col justify-between shadow-[0_0_40px_rgba(249,115,22,0.2)] relative scale-105 z-10">
            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3.5 py-1 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 text-[10px] font-extrabold uppercase tracking-wider text-white shadow-md">
                Most Popular / الأكثر طلباً
            </div>

            <div>
                <h3 class="text-xl font-bold text-white">Professional / خطة المحترفين</h3>
                <p class="text-xs text-zinc-400 mt-1">Full AI battlecards, hourly monitoring & automated alerts.</p>
                <div class="mt-6 flex items-baseline gap-1">
                    <span class="text-4xl font-extrabold text-white font-mono price-pro">3,490</span>
                    <span class="text-xs text-orange-400 font-bold currency-unit">ج.م</span>
                    <span class="text-xs text-zinc-500">/ {{ __('marketing.billing_monthly') }}</span>
                </div>

                <ul class="mt-8 space-y-3.5 text-xs text-zinc-300">
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        50 Tracked Competitor Profiles
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Hourly Automated Scraping & Detection
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Deep Gemini Competitive Gap Analysis
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Full PDF / Excel Intelligence Dossiers
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Multi-channel WhatsApp & Email Alerts
                    </li>
                </ul>
            </div>

            <div class="mt-8">
                <a href="#live-demo" class="w-full block py-3 rounded-full text-center text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-400 hover:to-amber-500 shadow-[0_0_25px_rgba(249,115,22,0.4)] transition-all">
                    Claim 14-Day Free Trial
                </a>
            </div>
        </div>

        <!-- Plan 3: Enterprise -->
        <div class="p-8 rounded-3xl bg-zinc-900/40 border border-zinc-800 backdrop-blur-xl flex flex-col justify-between hover:border-zinc-700 transition-all">
            <div>
                <h3 class="text-xl font-bold text-white">Enterprise / خطة المؤسسات</h3>
                <p class="text-xs text-zinc-400 mt-1">Dedicated tenant partition & custom AI strategy models.</p>
                <div class="mt-6 flex items-baseline gap-1">
                    <span class="text-4xl font-extrabold text-white font-mono price-enterprise">9,990</span>
                    <span class="text-xs text-orange-400 font-bold currency-unit">ج.م</span>
                    <span class="text-xs text-zinc-500">/ {{ __('marketing.billing_monthly') }}</span>
                </div>

                <ul class="mt-8 space-y-3.5 text-xs text-zinc-300">
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Unlimited Competitor Profiles
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Real-Time Multi-Agent Scraping
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Isolated Multi-Tenant Database
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Custom AI Reasoning & 24/7 SLA
                    </li>
                </ul>
            </div>

            <div class="mt-8">
                <a href="{{ route('contact') }}" class="w-full block py-3 rounded-full text-center text-xs font-bold text-white bg-zinc-800 hover:bg-zinc-700 transition-colors">
                    Contact Enterprise Team
                </a>
            </div>
        </div>

    </div>
</section>

<script>
    let currentCurrency = 'egp';
    let currentBilling = 'monthly';

    const prices = {
        egp: {
            monthly: { starter: '1,490', pro: '3,490', enterprise: '9,990', unit: 'ج.م' },
            annual: { starter: '1,190', pro: '2,890', enterprise: '7,990', unit: 'ج.م' }
        },
        usd: {
            monthly: { starter: '$29', pro: '$69', enterprise: '$199', unit: 'USD' },
            annual: { starter: '$24', pro: '$55', enterprise: '$159', unit: 'USD' }
        }
    };

    function updatePrices() {
        const data = prices[currentCurrency][currentBilling];
        document.querySelector('.price-starter').innerText = data.starter;
        document.querySelector('.price-pro').innerText = data.pro;
        document.querySelector('.price-enterprise').innerText = data.enterprise;
        document.querySelectorAll('.currency-unit').forEach(el => el.innerText = data.unit);
    }

    function setCurrency(cur) {
        currentCurrency = cur;
        const egpBtn = document.getElementById('curEgpBtn');
        const usdBtn = document.getElementById('curUsdBtn');

        if (cur === 'egp') {
            egpBtn.className = "px-4 py-1.5 rounded-full text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 shadow-md transition-all";
            usdBtn.className = "px-4 py-1.5 rounded-full text-xs font-bold text-zinc-400 hover:text-white transition-all";
        } else {
            egpBtn.className = "px-4 py-1.5 rounded-full text-xs font-bold text-zinc-400 hover:text-white transition-all";
            usdBtn.className = "px-4 py-1.5 rounded-full text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 shadow-md transition-all";
        }
        updatePrices();
    }

    function setBilling(cycle) {
        currentBilling = cycle;
        const monthlyBtn = document.getElementById('monthlyBtn');
        const annualBtn = document.getElementById('annualBtn');

        if (cycle === 'annual') {
            monthlyBtn.className = "px-5 py-1.5 rounded-full text-xs font-semibold text-zinc-400 hover:text-white transition-all";
            annualBtn.className = "px-5 py-1.5 rounded-full text-xs font-semibold text-white bg-orange-500 shadow-md transition-all";
        } else {
            monthlyBtn.className = "px-5 py-1.5 rounded-full text-xs font-semibold text-white bg-orange-500 shadow-md transition-all";
            annualBtn.className = "px-5 py-1.5 rounded-full text-xs font-semibold text-zinc-400 hover:text-white transition-all";
        }
        updatePrices();
    }
</script>
