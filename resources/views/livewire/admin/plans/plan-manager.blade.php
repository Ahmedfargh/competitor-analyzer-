<div>
    <!-- Header Actions & Currency Switcher -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ __('admin.manage_plans') }}</h1>
            <p class="text-xs text-zinc-400 mt-1">{{ __('admin.plans_desc') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Currency Toggle -->
            <div class="inline-flex items-center p-1 rounded-2xl bg-zinc-900/90 border border-white/10 shadow-inner">
                <button type="button" wire:click="setCurrency('egp')"
                        class="px-4 py-1.5 rounded-xl text-xs font-bold transition-all {{ $currency === 'egp' ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-md' : 'text-zinc-400 hover:text-white' }}">
                    EGP (ج.م)
                </button>
                <button type="button" wire:click="setCurrency('usd')"
                        class="px-4 py-1.5 rounded-xl text-xs font-bold transition-all {{ $currency === 'usd' ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-md' : 'text-zinc-400 hover:text-white' }}">
                    USD ($)
                </button>
            </div>

            <!-- Create Plan Button -->
            <button type="button" wire:click="openCreateModal"
                    class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white font-extrabold text-xs shadow-[0_0_20px_rgba(249,115,22,0.35)] transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>{{ __('admin.add_plan') }}</span>
            </button>
        </div>
    </div>

    <!-- Status Message Alert -->
    @if ($statusMessage)
        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 text-xs flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium">{{ $statusMessage }}</span>
            </div>
            <button type="button" wire:click="$set('statusMessage', null)" class="text-zinc-500 hover:text-white">✕</button>
        </div>
    @endif

    <!-- Plan Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        @foreach ($plans as $plan)
            <div class="card-luxury rounded-3xl p-6 sm:p-7 relative transition-all duration-300 flex flex-col justify-between {{ $plan->is_popular ? 'ring-1 ring-orange-500/50 shadow-[0_0_30px_rgba(249,115,22,0.15)]' : '' }}" wire:key="plan-card-{{ $plan->id }}">
                @if ($plan->is_popular)
                    <div class="absolute -top-3.5 end-6 px-3.5 py-1 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 text-[10px] font-black text-white uppercase tracking-wider shadow-lg">
                        Recommended
                    </div>
                @endif

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="text-lg font-black text-white tracking-tight">{{ $plan->name }}</h3>
                        <span class="text-xs font-mono text-orange-400 bg-orange-500/10 px-2 py-0.5 rounded-lg border border-orange-500/20">
                            #{{ $plan->slug }}
                        </span>
                    </div>
                    @php
                        $altLocale = app()->getLocale() === 'en' ? 'ar' : 'en';
                        $altName = $plan->getTranslation('name', $altLocale, false);
                    @endphp
                    @if ($altName)
                        <div class="text-[11px] text-zinc-400 font-medium mb-3 flex items-center gap-1.5">
                            <span class="px-1.5 py-0.5 rounded bg-zinc-800 text-[10px] uppercase font-mono text-zinc-300">{{ $altLocale }}</span>
                            <span>{{ $altName }}</span>
                        </div>
                    @endif

                    <p class="text-xs text-zinc-400 mb-6 leading-relaxed">{{ $plan->description }}</p>

                    <!-- Price Box with Dynamic Currency Display -->
                    <div class="mb-6 p-4 rounded-2xl bg-zinc-950/70 border border-white/[0.06]">
                        @if ($currency === 'egp')
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl sm:text-4xl font-black text-white font-mono tracking-tight">
                                    {{ number_format((float) $plan->price_egp, 0) }}
                                </span>
                                <span class="text-xs font-bold text-orange-400">ج.م (EGP)</span>
                                <span class="text-xs text-zinc-500">/ {{ $plan->billing_period }}</span>
                            </div>
                            <p class="text-[11px] text-zinc-500 mt-1 font-mono">
                                ≈ ${{ number_format((float) $plan->price_usd, 0) }} USD
                            </p>
                        @else
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl sm:text-4xl font-black text-white font-mono tracking-tight">
                                    ${{ number_format((float) $plan->price_usd, 0) }}
                                </span>
                                <span class="text-xs font-bold text-orange-400">USD</span>
                                <span class="text-xs text-zinc-500">/ {{ $plan->billing_period }}</span>
                            </div>
                            <p class="text-[11px] text-zinc-500 mt-1 font-mono">
                                ≈ {{ number_format((float) $plan->price_egp, 0) }} ج.م EGP
                            </p>
                        @endif
                    </div>

                    <!-- Features -->
                    @if (!empty($plan->features))
                        <ul class="space-y-2.5 text-xs text-zinc-300 mb-6">
                            @foreach ($plan->features as $feature)
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-orange-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Footer Actions -->
                <div class="pt-4 border-t border-white/[0.06] flex items-center justify-end gap-2">
                    <button type="button" wire:click="openEditModal({{ $plan->id }})"
                            class="px-3.5 py-1.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-xs font-bold text-zinc-200 transition-colors">
                        Edit
                    </button>
                    <button type="button" wire:click="deletePlan({{ $plan->id }})"
                            wire:confirm="Delete this subscription tier?"
                            class="px-3.5 py-1.5 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-xs font-bold text-red-400 transition-colors">
                        Delete
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- INLINE CREATE/EDIT PLAN MODAL -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md animate-fade-in">
            <div class="card-luxury w-full max-w-xl rounded-3xl p-7 relative shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-white/[0.08] mb-6">
                    <h3 class="text-xl font-black text-white tracking-tight">
                        {{ $editingPlanId ? 'Edit Subscription Plan' : __('admin.add_plan') }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="p-1.5 rounded-xl text-zinc-400 hover:text-white hover:bg-white/10">✕</button>
                </div>

                <!-- Language Tabs -->
                <div class="flex items-center gap-2 p-1 rounded-2xl bg-zinc-950 border border-white/10 mb-4">
                    <button type="button" wire:click="$set('activeTab', 'en')"
                            class="flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 {{ $activeTab === 'en' ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-md' : 'text-zinc-400 hover:text-white' }}">
                        <span>🇬🇧 English</span>
                    </button>
                    <button type="button" wire:click="$set('activeTab', 'ar')"
                            class="flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 {{ $activeTab === 'ar' ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-md' : 'text-zinc-400 hover:text-white' }}">
                        <span>🇪🇬 العربية (Arabic)</span>
                    </button>
                </div>

                <form wire:submit.prevent="savePlan" class="space-y-4 text-xs">
                    <!-- English Fields Tab -->
                    <div class="{{ $activeTab === 'en' ? 'space-y-4' : 'hidden' }}">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                                Plan Name (English) <span class="text-orange-400">*</span>
                            </label>
                            <input type="text" wire:model="name_en" placeholder="e.g. Growth Tier"
                                   class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs">
                            @error('name_en') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">Description (English)</label>
                            <textarea wire:model="description_en" rows="2" placeholder="Plan overview in English..."
                                      class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs"></textarea>
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">Features (English, one per line)</label>
                            <textarea wire:model="features_en" rows="3" placeholder="Up to 20 Competitor Profiles&#10;Daily Automated Scrapes"
                                      class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs"></textarea>
                        </div>
                    </div>

                    <!-- Arabic Fields Tab -->
                    <div class="{{ $activeTab === 'ar' ? 'space-y-4' : 'hidden' }}">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                                اسم الخطة (بالعربية)
                            </label>
                            <input type="text" wire:model="name_ar" dir="rtl" placeholder="مثال: باقة النمو"
                                   class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs text-right">
                            @error('name_ar') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">الوصف (بالعربية)</label>
                            <textarea wire:model="description_ar" dir="rtl" rows="2" placeholder="وصف الخطة باللغة العربية..."
                                      class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs text-right"></textarea>
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">المميزات (بالعربية، ميزة في كل سطر)</label>
                            <textarea wire:model="features_ar" dir="rtl" rows="3" placeholder="حتى 20 ملف منافس&#10;فحص آلي يومي"
                                      class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs text-right"></textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">Slug Identifier</label>
                        <input type="text" wire:model="slug" placeholder="e.g. growth"
                               class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white font-mono focus:border-orange-500 focus:outline-none text-xs">
                        @error('slug') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">Price in EGP (ج.م)</label>
                            <input type="number" step="0.01" wire:model="price_egp"
                                   class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white font-mono focus:border-orange-500 focus:outline-none text-xs">
                            @error('price_egp') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">Price in USD ($)</label>
                            <input type="number" step="0.01" wire:model="price_usd"
                                   class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white font-mono focus:border-orange-500 focus:outline-none text-xs">
                            @error('price_usd') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">Billing Period</label>
                        <select wire:model="billing_period"
                                class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs">
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            <option value="lifetime">Lifetime</option>
                        </select>
                        @error('billing_period') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer text-zinc-300 font-bold">
                            <input type="checkbox" wire:model="is_popular" class="w-4 h-4 rounded bg-zinc-950 border-white/10 text-orange-500">
                            <span>Recommended</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer text-zinc-300 font-bold">
                            <input type="checkbox" wire:model="is_active" class="w-4 h-4 rounded bg-zinc-950 border-white/10 text-orange-500">
                            <span>Active</span>
                        </label>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/[0.08]">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-xl text-zinc-400 hover:text-white font-bold">
                            {{ __('admin.cancel') }}
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white font-black shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all">
                            Save Tier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
