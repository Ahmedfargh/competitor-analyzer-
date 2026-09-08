<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.edit_tenant') }}: {{ $tenant->company_name }}
    </x-slot:header>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="text-xs text-zinc-400 hover:text-white flex items-center gap-1 mb-2">
                <span>←</span>
                <span>{{ $tenant->company_name }}</span>
            </a>
            <h1 class="text-2xl font-black text-white tracking-tight">{{ __('admin.edit_tenant') }}</h1>
            <p class="text-xs text-zinc-400 mt-1">Tenant ID: <span class="font-mono text-orange-400">{{ $tenant->id }}</span></p>
        </div>

        <div class="p-8 rounded-3xl bg-zinc-900/70 backdrop-blur-xl border border-white/10 shadow-2xl relative">
            <form action="{{ route('admin.tenants.update', $tenant->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Company Name -->
                <div>
                    <label for="company_name" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                        {{ __('admin.company_name') }}
                    </label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $tenant->company_name) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm">
                </div>

                <!-- Domain -->
                <div>
                    <label for="domain" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                        {{ __('admin.domain') }}
                    </label>
                    <input type="text" id="domain" name="domain" value="{{ old('domain', $tenant->primary_domain) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 font-mono text-sm">
                </div>

                <!-- Plan Assignment -->
                <div>
                    <label for="plan_id" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                        {{ __('admin.assigned_plan') }}
                    </label>
                    <select id="plan_id" name="plan_id"
                            class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white focus:outline-none focus:border-orange-500 text-sm">
                        <option value="">-- {{ __('admin.no_plan') }} --</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" {{ (old('plan_id', $tenant->data['plan_id'] ?? null) == $plan->id) ? 'selected' : '' }}>
                                {{ $plan->name }} — {{ number_format((float) $plan->price_egp, 0) }} ج.م (${{ number_format((float) $plan->price_usd, 0) }} USD)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Product Profile & Competitive Intelligence Context -->
                <div class="pt-6 border-t border-white/10 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <h3 class="text-sm font-bold text-white tracking-wide">Tenant Product Profile (AI Competitive Intelligence Context)</h3>
                    </div>
                    <p class="text-xs text-zinc-400">Define this tenant's core solution parameters to power 1-to-1 Head-to-Head Comparative Gap Analysis against competitors.</p>

                    <div>
                        <label for="product_name" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-1">
                            Product / Solution Name
                        </label>
                        <input type="text" id="product_name" name="product_name"
                               value="{{ old('product_name', $tenant->product_profile['product_name'] ?? $tenant->company_name) }}"
                               placeholder="e.g. Acme CRM Suite"
                               class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm">
                    </div>

                    <div>
                        <label for="value_proposition" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-1">
                            Core Value Proposition
                        </label>
                        <textarea id="value_proposition" name="value_proposition" rows="2"
                                  placeholder="e.g. The fastest Arabic-first CRM designed specifically for high-growth real estate teams."
                                  class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm">{{ old('value_proposition', $tenant->product_profile['value_proposition'] ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="pricing_summary" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-1">
                                Pricing Structure Summary
                            </label>
                            <input type="text" id="pricing_summary" name="pricing_summary"
                                   value="{{ old('pricing_summary', $tenant->product_profile['pricing_summary'] ?? '') }}"
                                   placeholder="e.g. $49/mo flat fee, unlimited seats"
                                   class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm">
                        </div>

                        <div>
                            <label for="target_icp" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-1">
                                Ideal Customer Profile (ICP)
                            </label>
                            <input type="text" id="target_icp" name="target_icp"
                                   value="{{ old('target_icp', $tenant->product_profile['target_icp'] ?? '') }}"
                                   placeholder="e.g. Brokerages with 10-50 agents in MENA"
                                   class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="key_differentiators" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-1">
                            Key Differentiators & Moats (one per line)
                        </label>
                        <textarea id="key_differentiators" name="key_differentiators" rows="3"
                                  placeholder="Native WhatsApp direct sync&#10;Zero setup fees&#10;Sub-hour support SLA"
                                  class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm font-mono">{{ old('key_differentiators', implode("\n", (array) ($tenant->product_profile['key_differentiators'] ?? []))) }}</textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/5">
                    <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-zinc-400 hover:text-white transition-colors">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit"
                            class="px-6 py-3 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all">
                        {{ __('admin.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin::layouts.master>
