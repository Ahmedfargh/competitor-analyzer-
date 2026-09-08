<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.add_plan') }}
    </x-slot:header>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.plans.index') }}" class="text-xs text-zinc-400 hover:text-white flex items-center gap-1 mb-2">
                <span>←</span>
                <span>{{ __('admin.plans') }}</span>
            </a>
            <h1 class="text-2xl font-black text-white tracking-tight">{{ __('admin.add_plan') }}</h1>
            <p class="text-xs text-zinc-400 mt-1">Define pricing in Egyptian Pounds (EGP) and USD equivalent.</p>
        </div>

        <div class="p-8 rounded-3xl bg-zinc-900/70 backdrop-blur-xl border border-white/10 shadow-2xl relative">
            <form action="{{ route('admin.plans.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Plan Names (EN & AR) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name_en" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                            Plan Name (English) <span class="text-orange-400">*</span>
                        </label>
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en', old('name')) }}" required placeholder="e.g. Growth Tier"
                               class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm">
                    </div>
                    <div>
                        <label for="name_ar" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                            اسم الخطة (العربية)
                        </label>
                        <input type="text" id="name_ar" name="name_ar" dir="rtl" value="{{ old('name_ar') }}" placeholder="مثال: باقة النمو"
                               class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm text-right">
                    </div>
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                        Slug Identifier <span class="text-orange-400">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required placeholder="e.g. growth"
                           class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 font-mono text-sm">
                </div>

                <!-- Descriptions (EN & AR) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="description_en" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                            Description (English)
                        </label>
                        <textarea id="description_en" name="description_en" rows="2" placeholder="Summary of this plan tier..."
                                  class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm">{{ old('description_en', old('description')) }}</textarea>
                    </div>
                    <div>
                        <label for="description_ar" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                            الوصف (العربية)
                        </label>
                        <textarea id="description_ar" name="description_ar" dir="rtl" rows="2" placeholder="ملخص عن باقة الاشتراك..."
                                  class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm text-right">{{ old('description_ar') }}</textarea>
                    </div>
                </div>

                <!-- Prices in EGP and USD -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="price_egp" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                            {{ __('admin.price_egp') }} <span class="text-orange-400">*</span>
                        </label>
                        <input type="number" step="0.01" id="price_egp" name="price_egp" value="{{ old('price_egp', '1490.00') }}" required
                               class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 font-mono text-sm">
                    </div>

                    <div>
                        <label for="price_usd" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                            {{ __('admin.price_usd') }} <span class="text-orange-400">*</span>
                        </label>
                        <input type="number" step="0.01" id="price_usd" name="price_usd" value="{{ old('price_usd', '29.00') }}" required
                               class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 font-mono text-sm">
                    </div>
                </div>

                <!-- Billing Period -->
                <div>
                    <label for="billing_period" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                        {{ __('admin.billing_period') }}
                    </label>
                    <select id="billing_period" name="billing_period"
                            class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white focus:outline-none focus:border-orange-500 text-sm">
                        <option value="monthly" {{ old('billing_period') === 'monthly' ? 'selected' : '' }}>{{ __('admin.monthly') }}</option>
                        <option value="yearly" {{ old('billing_period') === 'yearly' ? 'selected' : '' }}>{{ __('admin.yearly') }}</option>
                        <option value="lifetime" {{ old('billing_period') === 'lifetime' ? 'selected' : '' }}>{{ __('admin.lifetime') }}</option>
                    </select>
                </div>

                <!-- Features list -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                        Features (one per line)
                    </label>
                    <textarea name="features[]" rows="4" placeholder="Up to 20 Competitor Profiles&#10;Daily Automated Scraper Check&#10;Gemini Flash AI Matrix"
                              class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm">{{ is_array(old('features')) ? implode("\n", old('features')) : '' }}</textarea>
                </div>

                <!-- Checkboxes -->
                <div class="flex items-center gap-6 text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-zinc-300">
                        <input type="checkbox" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}
                               class="w-4 h-4 rounded bg-zinc-950 border-white/10 text-orange-500 focus:ring-orange-500/20">
                        <span>{{ __('admin.is_popular') }}</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer text-zinc-300">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded bg-zinc-950 border-white/10 text-orange-500 focus:ring-orange-500/20">
                        <span>{{ __('admin.is_active') }}</span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/5">
                    <a href="{{ route('admin.plans.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-zinc-400 hover:text-white transition-colors">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit"
                            class="px-6 py-3 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all">
                        {{ __('admin.add_plan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin::layouts.master>
