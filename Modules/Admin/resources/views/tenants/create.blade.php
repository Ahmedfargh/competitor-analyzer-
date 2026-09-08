<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.create_tenant_title') }}
    </x-slot:header>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.tenants.index') }}" class="text-xs text-zinc-400 hover:text-white flex items-center gap-1 mb-2">
                <span>←</span>
                <span>{{ __('admin.tenants') }}</span>
            </a>
            <h1 class="text-2xl font-black text-white tracking-tight">{{ __('admin.create_tenant_title') }}</h1>
            <p class="text-xs text-zinc-400 mt-1">{{ __('admin.create_tenant_subtitle') }}</p>
        </div>

        <div class="p-8 rounded-3xl bg-zinc-900/70 backdrop-blur-xl border border-white/10 shadow-2xl relative">
            <div class="absolute -top-px left-8 right-8 h-px bg-gradient-to-r from-transparent via-orange-500/40 to-transparent"></div>

            <form action="{{ route('admin.tenants.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Tenant Identifier (Slug) -->
                <div>
                    <label for="id" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                        {{ __('admin.tenant_id') }} <span class="text-orange-400">*</span>
                    </label>
                    <input type="text" id="id" name="id" value="{{ old('id') }}" required placeholder="e.g. acme-enterprise"
                           class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 font-mono text-sm">
                    <p class="text-[11px] text-zinc-500 mt-1.5">Letters, numbers, and dashes only. Must be unique.</p>
                </div>

                <!-- Company Name -->
                <div>
                    <label for="company_name" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                        {{ __('admin.company_name') }} <span class="text-orange-400">*</span>
                    </label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required placeholder="e.g. Acme Corporation"
                           class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 text-sm">
                </div>

                <!-- Domain -->
                <div>
                    <label for="domain" class="block text-xs font-semibold uppercase tracking-wider text-zinc-300 mb-2">
                        {{ __('admin.domain') }} <span class="text-orange-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="domain" name="domain" value="{{ old('domain') }}" required placeholder="e.g. acme or acme.194.164.77.152.nip.io"
                               class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-600 focus:outline-none focus:border-orange-500 font-mono text-sm">
                    </div>
                    <p class="text-[11px] text-zinc-500 mt-1.5">{{ __('admin.domain_help') }}</p>
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
                            <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} — {{ number_format((float) $plan->price_egp, 0) }} ج.م / {{ $plan->billing_period }} (${{ number_format((float) $plan->price_usd, 0) }} USD)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/5">
                    <a href="{{ route('admin.tenants.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-zinc-400 hover:text-white transition-colors">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit"
                            class="px-6 py-3 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all">
                        {{ __('admin.provision_tenant') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin::layouts.master>
