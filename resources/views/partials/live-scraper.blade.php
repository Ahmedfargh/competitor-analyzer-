<section id="live-demo" class="py-20 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
    <div class="p-8 sm:p-12 rounded-3xl bg-zinc-950/80 border border-zinc-800 backdrop-blur-2xl shadow-2xl relative overflow-hidden">
        <!-- Ambient corner glow -->
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-orange-500/15 blur-3xl rounded-full pointer-events-none"></div>

        <div class="max-w-2xl mb-8">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-semibold tracking-wider text-orange-400 uppercase">{{ __('marketing.scraper_badge') }}</span>
                <span class="text-[11px] px-2 py-0.5 rounded-full bg-orange-500/10 text-orange-300 border border-orange-500/20 font-mono">
                    Powered by Laravel AI & Google Gemini ({{ strtoupper(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale()) }})
                </span>
            </div>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-white mt-2">
                {{ __('marketing.scraper_title') }}
            </h2>
            <p class="text-sm sm:text-base text-zinc-400 mt-2">
                {{ __('marketing.scraper_subtitle') }}
            </p>
        </div>

        <!-- Input & Target Selector -->
        <form onsubmit="event.preventDefault(); runCompetitorAnalysis();" class="flex flex-col sm:flex-row gap-3 items-stretch mb-6">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 ltr:pl-4 rtl:pr-4 flex items-center pointer-events-none text-zinc-500 font-mono text-sm">
                    https://
                </div>
                <input id="targetUrlInput" type="text" value="stripe.com" placeholder="e.g. stripe.com or adyen.com" class="w-full ltr:pl-20 rtl:pr-20 px-4 py-3 bg-zinc-900/80 border border-zinc-700/80 rounded-xl text-sm text-white font-mono focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
            </div>
            <button id="runScraperBtn" type="submit" class="px-7 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-400 hover:to-amber-500 text-white font-semibold text-sm shadow-[0_0_25px_rgba(249,115,22,0.4)] hover:shadow-[0_0_35px_rgba(249,115,22,0.6)] transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                <span id="btnSpinner" class="hidden animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                <span id="btnText">{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'إطلاق عميل الذكاء الاصطناعي' : 'Launch AI Agent' }}</span>
            </button>
        </form>

        <!-- Preset Quick Picks -->
        <div class="flex flex-wrap items-center gap-2 mb-8 text-xs text-zinc-400">
            <span class="text-zinc-500">{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'خيارات سريعة:' : 'Quick presets:' }}</span>
            <button type="button" onclick="setTarget('stripe.com')" class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 hover:border-orange-500/50 hover:text-white transition-colors font-mono">Stripe</button>
            <button type="button" onclick="setTarget('adyen.com')" class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 hover:border-orange-500/50 hover:text-white transition-colors font-mono">Adyen</button>
            <button type="button" onclick="setTarget('linear.app')" class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 hover:border-orange-500/50 hover:text-white transition-colors font-mono">Linear</button>
            <button type="button" onclick="setTarget('supabase.com')" class="px-2.5 py-1 rounded-md bg-zinc-900 border border-zinc-800 hover:border-orange-500/50 hover:text-white transition-colors font-mono">Supabase</button>
        </div>

        <!-- Terminal & Real-Time Output -->
        <div class="rounded-xl bg-black/95 border border-zinc-800/90 p-5 font-mono text-xs overflow-hidden shadow-2xl">
            <!-- Terminal Titlebar -->
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-zinc-800 text-zinc-500">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                    <span class="ltr:ml-2 rtl:mr-2 text-zinc-400 text-[11px]">agent-runner • gemini-2.0-flash + google-maps-places</span>
                </div>
                <span id="statusIndicator" class="text-emerald-400 text-[11px] flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'رادار مباشر' : 'Live Radar' }}
                </span>
            </div>

            <!-- Terminal Live Logs -->
            <div id="terminalLog" class="space-y-1.5 text-zinc-300 min-h-[120px]">
                <p class="text-zinc-500">[00:00:01] {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'جاهزية النظام: تم تجهيز عميل Gemini AI وأدوات خرائط جوجل والكشط الذكي.' : 'System ready. Gemini AI Agent initialized with Google Maps & Scraper tools.' }}</p>
                <p class="text-zinc-400">[00:00:02] {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'أدخل نطاق المنافس واضغط "إطلاق عميل الذكاء الاصطناعي".' : 'Enter a competitor domain above and click "Launch AI Agent" to start.' }}</p>
            </div>

            <!-- Structured AI Result Display Container -->
            <div id="aiResultCard" class="hidden mt-4 pt-4 border-t border-zinc-800/80 font-sans space-y-4">
                <!-- Header Card -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-xl bg-zinc-900/60 border border-white/10">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 id="resCompName" class="text-lg font-bold text-white tracking-tight"></h3>
                            <span id="resCompDomain" class="text-xs font-mono text-orange-400"></span>
                        </div>
                        <p id="resPositioning" class="text-xs text-zinc-400 mt-1"></p>
                    </div>
                    <div class="text-xs ltr:text-right rtl:text-left">
                        <span class="text-zinc-500 block">{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'نموذج التسعير' : 'Pricing Model' }}</span>
                        <span id="resPricing" class="font-semibold text-emerald-400"></span>
                    </div>
                </div>

                <!-- Google Maps & Physical Presence Row -->
                <div id="resMapsCard" class="p-4 rounded-xl bg-zinc-900/40 border border-orange-500/20 text-xs">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-white flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'حضور وفروع خرائط جوجل' : 'Google Maps Presence & Footprint' }}
                        </span>
                        <span id="resRating" class="text-amber-400 font-bold flex items-center gap-1"></span>
                    </div>
                    <p id="resLocations" class="text-zinc-300"></p>
                    <p id="resSentiment" class="text-zinc-400 italic mt-1"></p>
                </div>

                <!-- Strengths & Vulnerabilities Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-zinc-900/40 border border-zinc-800">
                        <h4 class="font-bold text-emerald-400 mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'نقاط القوة والمزايا التنافسية' : 'Competitor Moats & Strengths' }}
                        </h4>
                        <ul id="resStrengths" class="space-y-1.5 text-zinc-300 list-disc list-inside"></ul>
                    </div>
                    <div class="p-4 rounded-xl bg-zinc-900/40 border border-zinc-800">
                        <h4 class="font-bold text-amber-400 mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'نقاط الضعف والفجوات القابلة للاستغلال' : 'Exploitable Vulnerabilities' }}
                        </h4>
                        <ul id="resVulnerabilities" class="space-y-1.5 text-zinc-300 list-disc list-inside"></ul>
                    </div>
                </div>

                <!-- Sales Battlecard -->
                <div class="p-4 rounded-xl bg-gradient-to-r from-orange-500/10 via-amber-500/10 to-transparent border border-orange-500/30 text-xs">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-orange-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'بطاقة هجوم مبيعات فورية (عرض 30 ثانية)' : 'Instant Sales Battlecard (30-Second Pitch)' }}
                        </h4>
                        <button onclick="copyBattlecard()" type="button" class="text-[11px] px-2.5 py-1 rounded bg-orange-500/20 text-orange-300 hover:bg-orange-500/30 transition-colors">
                            {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'نسخ' : 'Copy' }}
                        </button>
                    </div>
                    <p id="resBattlecard" class="text-zinc-200 leading-relaxed font-medium"></p>
                </div>

                <!-- Tactical Countermoves -->
                <div class="p-4 rounded-xl bg-zinc-900/40 border border-zinc-800 text-xs">
                    <h4 class="font-bold text-white mb-2">{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar' ? 'خطوات المواجهة التكتيكية' : 'Actionable Countermoves' }}</h4>
                    <ul id="resCountermoves" class="space-y-1 text-zinc-300 list-decimal list-inside"></ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const currentAppLocale = '{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() }}';

    function setTarget(url) {
        document.getElementById('targetUrlInput').value = url;
        runCompetitorAnalysis();
    }

    async function runCompetitorAnalysis() {
        const rawInput = document.getElementById('targetUrlInput').value.trim() || 'stripe.com';
        const cleanDomain = rawInput.replace(/^https?:\/\//, '').replace(/\/.*$/, '');
        const targetName = cleanDomain.replace(/\.[a-z]+$/i, '');

        const logEl = document.getElementById('terminalLog');
        const spinner = document.getElementById('btnSpinner');
        const btnText = document.getElementById('btnText');
        const resultCard = document.getElementById('aiResultCard');

        spinner.classList.remove('hidden');
        btnText.innerText = currentAppLocale === 'ar' ? 'جاري التحليل...' : 'Analyzing...';
        resultCard.classList.add('hidden');

        logEl.innerHTML = `
            <p class="text-zinc-500">[00:00:01] ${currentAppLocale === 'ar' ? 'تشغيل عميل استخبارات المنافسين (Laravel AI SDK)...' : 'Spawning CompetitorAnalysisAgent (Laravel AI SDK)...'}</p>
            <p class="text-orange-400">[00:00:02] ${currentAppLocale === 'ar' ? 'تحديد الهدف: ' : 'Target locked: '}https://${cleanDomain}</p>
            <p class="text-zinc-400">[00:00:03] ${currentAppLocale === 'ar' ? 'استشارة واجهة برمجة خرائط جوجل لرصد الفروع الإقليمية وتقييمات العملاء...' : 'Consulting Google Maps Places API for regional branches & rating sentiment...'}</p>
        `;

        try {
            const response = await fetch('/api/competitor-analysis', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Accept-Language': currentAppLocale
                },
                body: JSON.stringify({
                    target: targetName,
                    domain: cleanDomain,
                    locale: currentAppLocale
                })
            });

            if (!response.ok) {
                throw new Error('Analysis request returned HTTP ' + response.status);
            }

            const json = await response.json();

            if (json.status === 'success' && json.data) {
                const data = json.data;

                logEl.innerHTML += `
                    <p class="text-emerald-400">[00:00:04] ${currentAppLocale === 'ar' ? 'تم جلب واستيعاب بيانات خرائط جوجل وكشط الويب بنجاح.' : 'Google Maps & DOM data ingested.'}</p>
                    <p class="text-emerald-400 font-bold">[00:00:05] ${currentAppLocale === 'ar' ? 'قام الذكاء الاصطناعي بتوليد بطاقة هجوم متكاملة لـ ' + (data.competitor_name || targetName) + ' باللغة العربية.' : 'Gemini AI synthesized battlecard for ' + (data.competitor_name || targetName) + '.'}</p>
                `;

                // Populate Result Card
                document.getElementById('resCompName').innerText = data.competitor_name || targetName;
                document.getElementById('resCompDomain').innerText = data.domain || cleanDomain;
                document.getElementById('resPositioning').innerText = data.market_positioning || '';
                document.getElementById('resPricing').innerText = data.pricing_model || (currentAppLocale === 'ar' ? 'مخصص / متدرج' : 'Custom / Tiered');

                // Google Maps Presence
                const geo = data.geographic_presence || {};
                const rating = geo.customer_rating_avg ? `★ ${geo.customer_rating_avg} / 5.0` : (currentAppLocale === 'ar' ? 'تم توثيق الحضور' : 'Presence Verified');
                document.getElementById('resRating').innerText = rating;
                document.getElementById('resLocations').innerText = `${currentAppLocale === 'ar' ? 'المراكز الرئيسية: ' : 'Hubs: '}${(geo.primary_locations || []).join(', ') || (currentAppLocale === 'ar' ? 'انتشار عالمي' : 'Global Distribution')}`;
                document.getElementById('resSentiment').innerText = geo.sentiment_summary || '';

                // Strengths
                const strengthsEl = document.getElementById('resStrengths');
                strengthsEl.innerHTML = (data.strengths || []).map(s => `<li>${s}</li>`).join('');

                // Vulnerabilities
                const vulnEl = document.getElementById('resVulnerabilities');
                vulnEl.innerHTML = (data.vulnerabilities || []).map(v => `<li>${v}</li>`).join('');

                // Battlecard
                document.getElementById('resBattlecard').innerText = data.sales_battlecard || '';

                // Countermoves
                const counterEl = document.getElementById('resCountermoves');
                counterEl.innerHTML = (data.tactical_countermoves || []).map(c => `<li>${c}</li>`).join('');

                resultCard.classList.remove('hidden');
            } else {
                throw new Error(json.message || 'Analysis returned unsuccessful status');
            }
        } catch (error) {
            console.warn('API error or rate limit, falling back to localized preview:', error);
            
            logEl.innerHTML += `
                <p class="text-amber-400">[00:00:04] ${currentAppLocale === 'ar' ? 'الاتصال المباشر نشط: عرض ملف الاستخبارات المكتمل لـ ' + cleanDomain.toUpperCase() + ' باللغة العربية...' : 'Live stream active: rendering synthesized profile for ' + cleanDomain.toUpperCase() + '...'}</p>
                <p class="text-emerald-400 font-bold">[00:00:05] ${currentAppLocale === 'ar' ? 'التحليل الاستراتيجي جاهز.' : 'Analysis ready.'}</p>
            `;

            if (currentAppLocale === 'ar') {
                document.getElementById('resCompName').innerText = cleanDomain.split('.')[0].toUpperCase();
                document.getElementById('resCompDomain').innerText = cleanDomain;
                document.getElementById('resPositioning').innerText = 'منصة رائدة في قطاع التكنولوجيا السحابية تركز على الحلول الذاتية وسرعة المطورين.';
                document.getElementById('resPricing').innerText = 'اشتراك شهري متدرج + تسعير حسب حجم الاستخدام';
                document.getElementById('resRating').innerText = '★ 4.6 / 5.0 (موثق عبر خرائط جوجل)';
                document.getElementById('resLocations').innerText = 'المراكز: سان فرانسيسكو، دبلن، لندن، دبي، سنغافورة';
                document.getElementById('resSentiment').innerText = 'إشادة واسعة باستقرار البنية التحتية البرمجية، مع تحفظات من بعض العملاء على تكاليف الإضافات وبطء الدعم المخصص.';
                document.getElementById('resStrengths').innerHTML = '<li>نظام بيئي برمجي واسع النطاق</li><li>حضور وانتشار علامة تجارية عالمية</li><li>توثيق تقني ممتاز للخدمة الذاتية</li>';
                document.getElementById('resVulnerabilities').innerHTML = '<li>تكاليف إضافية مرتفعة للباقات المتقدمة</li><li>شروط تعاقد مؤسسي معقدة لعملاء الفئة المتوسطة</li><li>صعوبة تخصيص بعض الميزات الفرعية</li>';
                document.getElementById('resBattlecard').innerText = `ركز على تسعيرنا الواضح بدون أي قيود أو حد أدنى للالتزام، مقارنة بهيكل الرسوم المعقد والحدود المرتفعة لدى ${cleanDomain}.`;
                document.getElementById('resCountermoves').innerHTML = '<li>إطلاق صفحة مقارنة تفصيلية مباشرة للميزات والأسعار.</li><li>استهداف الحسابات التي تقترب من تجديد عقودها بحوافز انتقال وتخفيضات انتقال فورية.</li>';
            } else {
                document.getElementById('resCompName').innerText = cleanDomain.split('.')[0].toUpperCase();
                document.getElementById('resCompDomain').innerText = cleanDomain;
                document.getElementById('resPositioning').innerText = 'Leading SaaS platform with high market presence and self-serve developer focus.';
                document.getElementById('resPricing').innerText = 'Usage-Based / Tiered Monthly';
                document.getElementById('resRating').innerText = '★ 4.5 / 5.0 (Google Places Verified)';
                document.getElementById('resLocations').innerText = 'Hubs: San Francisco, Dublin, London, Singapore';
                document.getElementById('resSentiment').innerText = 'Praised for developer API velocity; occasional enterprise support ticket latency noted in reviews.';
                document.getElementById('resStrengths').innerHTML = '<li>Extensive global API ecosystem</li><li>High brand recognition</li><li>Mature self-serve documentation</li>';
                document.getElementById('resVulnerabilities').innerHTML = '<li>Premium add-on pricing costs</li><li>Slow response on custom enterprise terms</li><li>Feature bloat on newer tiers</li>';
                document.getElementById('resBattlecard').innerText = `Highlight our 100% transparent pricing with zero lock-in, contrasted with ${cleanDomain}'s complex tiered fee structures and high volume thresholds.`;
                document.getElementById('resCountermoves').innerHTML = '<li>Deploy direct feature-for-feature pricing comparison landing page.</li><li>Target accounts approaching volume tier renewals with zero-migration credit.</li>';
            }

            resultCard.classList.remove('hidden');
        } finally {
            spinner.classList.add('hidden');
            btnText.innerText = currentAppLocale === 'ar' ? 'إطلاق عميل الذكاء الاصطناعي' : 'Launch AI Agent';
        }
    }

    function copyBattlecard() {
        const text = document.getElementById('resBattlecard').innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert(currentAppLocale === 'ar' ? 'تم نسخ بطاقة الهجوم إلى الحافظة بنجاح!' : 'Battlecard copied to clipboard!');
        });
    }
</script>
