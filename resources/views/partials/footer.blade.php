<footer class="border-t border-zinc-900/80 bg-zinc-950/60 backdrop-blur-xl relative z-10 pt-16 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-10 pb-12 border-b border-zinc-900">
            <!-- Brand Column -->
            <div class="md:col-span-2 space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white font-bold text-sm shadow-[0_0_15px_rgba(249,115,22,0.4)]">
                        C
                    </div>
                    <span class="font-bold text-lg text-white tracking-tight">{{ __('marketing.brand_name') }} <span class="text-orange-400">{{ __('marketing.brand_ai') }}</span></span>
                </div>
                <p class="text-xs text-zinc-400 leading-relaxed max-w-sm">
                    {{ __('marketing.footer_tagline') }}
                </p>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[11px] font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    {{ __('marketing.footer_status') }}
                </div>
            </div>

            <!-- Col 1: Platform -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-300">{{ __('marketing.footer_product') }}</h4>
                <ul class="space-y-2 text-xs text-zinc-400">
                    <li><a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('features')) }}#scraping" class="hover:text-orange-400 transition-colors">{{ __('marketing.feature_1_title') }}</a></li>
                    <li><a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('features')) }}#ai-diffing" class="hover:text-orange-400 transition-colors">{{ __('marketing.feature_2_title') }}</a></li>
                    <li><a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('features')) }}#battlecards" class="hover:text-orange-400 transition-colors">{{ __('marketing.feature_3_title') }}</a></li>
                    <li><a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('features')) }}" class="hover:text-orange-400 transition-colors">{{ __('marketing.nav_features') }}</a></li>
                </ul>
            </div>

            <!-- Col 2: Company -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-300">{{ __('marketing.footer_company') }}</h4>
                <ul class="space-y-2 text-xs text-zinc-400">
                    <li><a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('about')) }}" class="hover:text-orange-400 transition-colors">{{ __('marketing.nav_about') }}</a></li>
                    <li><a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('pricing')) }}" class="hover:text-orange-400 transition-colors">{{ __('marketing.nav_pricing') }}</a></li>
                    <li><a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('contact')) }}" class="hover:text-orange-400 transition-colors">{{ __('marketing.nav_contact') }}</a></li>
                </ul>
            </div>

            <!-- Col 3: Compliance & Security -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-300">{{ __('marketing.footer_legal') }}</h4>
                <ul class="space-y-2 text-xs text-zinc-400">
                    <li><a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('about')) }}#ethics" class="hover:text-orange-400 transition-colors">{{ __('marketing.footer_soc2') }}</a></li>
                    <li><a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('about')) }}#compliance" class="hover:text-orange-400 transition-colors">{{ __('marketing.footer_gdpr') }}</a></li>
                    <li><a href="/login" class="hover:text-orange-400 transition-colors">{{ __('marketing.sign_in') }}</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-500">
            <p>© {{ date('Y') }} {{ __('marketing.brand_name') }} {{ __('marketing.brand_ai') }}. {{ __('marketing.footer_rights') }}</p>
            
            <!-- Footer Language Selector -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1.5 text-zinc-400">
                    <span>{{ __('marketing.language') }}:</span>
                    @foreach(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                           class="px-2 py-0.5 rounded border border-zinc-800 transition-colors {{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === $localeCode ? 'bg-orange-500/20 text-orange-400 border-orange-500/40 font-semibold' : 'hover:text-white' }}">
                            {{ $properties['native'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>
