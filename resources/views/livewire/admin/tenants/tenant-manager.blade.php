<div>
    <!-- Header Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ __('admin.tenants') }}</h1>
            <p class="text-xs text-zinc-400 mt-1">{{ __('admin.tenants_desc') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" wire:click="openCreateModal"
                    class="px-5 py-3 rounded-2xl bg-gradient-to-r from-orange-500 via-orange-600 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white font-extrabold text-xs shadow-[0_0_20px_rgba(249,115,22,0.35)] transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>{{ __('admin.provision_tenant') }}</span>
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

    <!-- Reactive Search & Plan Filter Toolbar -->
    <div class="card-luxury rounded-2xl p-4 mb-6">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="{{ __('admin.search_tenants') }}"
                       class="w-full ps-10 pe-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-500 text-xs focus:outline-none focus:border-orange-500 transition-colors">
                <svg class="w-4 h-4 text-zinc-500 absolute start-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <select wire:model.live="planFilter"
                    class="w-full sm:w-56 px-3 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-zinc-300 text-xs focus:outline-none focus:border-orange-500">
                <option value="">{{ __('All Subscription Tiers') }}</option>
                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}">
                        {{ $plan->name }} ({{ number_format((float) $plan->price_egp, 0) }} EGP)
                    </option>
                @endforeach
            </select>

            @if (!empty($search) || !empty($planFilter))
                <button type="button" wire:click="$set('search', ''); $set('planFilter', '');"
                        class="text-xs text-orange-400 hover:text-orange-300 px-2 py-1 font-semibold">
                    {{ __('Reset') }}
                </button>
            @endif
        </div>
    </div>

    <!-- Tenants Table -->
    <div class="card-luxury rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm text-zinc-300">
                <thead class="text-xs uppercase bg-zinc-950/80 text-zinc-400 border-b border-white/[0.06] font-mono text-[10px]">
                    <tr>
                        <th class="px-6 py-4 text-start">{{ __('admin.tenant_id') }}</th>
                        <th class="px-6 py-4 text-start">{{ __('admin.company_name') }}</th>
                        <th class="px-6 py-4 text-start">{{ __('admin.domain') }}</th>
                        <th class="px-6 py-4 text-start">{{ __('admin.assigned_plan') }}</th>
                        <th class="px-6 py-4 text-start">{{ __('admin.registered_at') }}</th>
                        <th class="px-6 py-4 text-end">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @forelse ($tenants as $tenant)
                        <tr class="hover:bg-white/[0.02] transition-colors" wire:key="tenant-row-{{ $tenant->id }}">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-orange-400 bg-orange-500/10 px-2.5 py-1 rounded-lg border border-orange-500/20">
                                    {{ $tenant->id }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-white">
                                {{ $tenant->company_name }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-zinc-300">
                                @if ($tenant->primary_url)
                                    <a href="{{ $tenant->primary_url }}" target="_blank" rel="noopener noreferrer" class="hover:text-orange-400 underline flex items-center gap-1">
                                        <span>{{ $tenant->primary_domain }}</span>
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-zinc-600">No domain</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($tenant->plan)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 border border-amber-500/20 text-amber-300">
                                        {{ $tenant->plan->name }} ({{ number_format((float) $tenant->plan->price_egp, 0) }} ج.م)
                                    </span>
                                @else
                                    <span class="text-xs text-zinc-500">{{ __('admin.no_plan') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-zinc-500">
                                {{ $tenant->created_at ? $tenant->created_at->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-end">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Browse Data -->
                                    <a href="{{ route('admin.tenants.show', $tenant->id) }}"
                                       title="{{ __('admin.view_data') }}"
                                       class="p-2 rounded-xl bg-orange-500/10 hover:bg-orange-500/25 text-orange-400 border border-orange-500/20 transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7zM9 12h6m-6 4h4"/>
                                        </svg>
                                    </a>

                                    <!-- Quick Edit Modal -->
                                    <button type="button" wire:click="editTenant('{{ $tenant->id }}')"
                                            title="{{ __('admin.edit_tenant') }}"
                                            class="p-2 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Delete -->
                                    <button type="button" wire:click="deleteTenant('{{ $tenant->id }}')"
                                            wire:confirm="{{ __('admin.confirm_delete') }}"
                                            title="{{ __('admin.delete_tenant') }}"
                                            class="p-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-zinc-500 text-xs">
                                {{ __('admin.no_tenants') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tenants->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>

    <!-- INLINE PROVISION MODAL -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md animate-fade-in">
            <div class="card-luxury w-full max-w-xl rounded-3xl p-7 relative shadow-2xl">
                <div class="flex items-center justify-between pb-4 border-b border-white/[0.08] mb-6">
                    <div>
                        <h3 class="text-xl font-black text-white tracking-tight">{{ __('admin.create_tenant_title') }}</h3>
                        <p class="text-xs text-zinc-400 mt-1">{{ __('admin.create_tenant_subtitle') }}</p>
                    </div>
                    <button type="button" wire:click="closeModals" class="p-1.5 rounded-xl text-zinc-400 hover:text-white hover:bg-white/10">✕</button>
                </div>

                <form wire:submit.prevent="provisionTenant" class="space-y-4 text-xs">
                    <!-- Tenant ID -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                            {{ __('admin.tenant_id') }} <span class="text-orange-400">*</span>
                        </label>
                        <input type="text" wire:model="tenant_id" placeholder="e.g. acme-enterprise"
                               class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white font-mono focus:border-orange-500 focus:outline-none text-xs">
                        @error('tenant_id') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                            {{ __('admin.company_name') }} <span class="text-orange-400">*</span>
                        </label>
                        <input type="text" wire:model="company_name" placeholder="e.g. Acme Corporation"
                               class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs">
                        @error('company_name') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Domain -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                            {{ __('admin.domain') }} <span class="text-orange-400">*</span>
                        </label>
                        <input type="text" wire:model="domain" placeholder="e.g. acme"
                               class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white font-mono focus:border-orange-500 focus:outline-none text-xs">
                        <p class="text-[10px] text-zinc-500 mt-1">{{ __('admin.domain_help') }}</p>
                        @error('domain') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Plan -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                            {{ __('admin.assigned_plan') }}
                        </label>
                        <select wire:model="plan_id"
                                class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs">
                            <option value="">-- {{ __('admin.no_plan') }} --</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">
                                    {{ $plan->name }} — {{ number_format((float) $plan->price_egp, 0) }} ج.م (${{ number_format((float) $plan->price_usd, 0) }} USD)
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/[0.08]">
                        <button type="button" wire:click="closeModals" class="px-4 py-2 rounded-xl text-zinc-400 hover:text-white font-bold">
                            {{ __('admin.cancel') }}
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white font-black shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all">
                            {{ __('admin.provision_tenant') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- INLINE EDIT MODAL -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md animate-fade-in">
            <div class="card-luxury w-full max-w-xl rounded-3xl p-7 relative shadow-2xl">
                <div class="flex items-center justify-between pb-4 border-b border-white/[0.08] mb-6">
                    <div>
                        <h3 class="text-xl font-black text-white tracking-tight">{{ __('admin.edit_tenant') }}</h3>
                        <p class="text-xs text-zinc-400 mt-1">Tenant ID: <span class="font-mono text-orange-400">{{ $editingTenantId }}</span></p>
                    </div>
                    <button type="button" wire:click="closeModals" class="p-1.5 rounded-xl text-zinc-400 hover:text-white hover:bg-white/10">✕</button>
                </div>

                <form wire:submit.prevent="saveEditedTenant" class="space-y-4 text-xs">
                    <!-- Company Name -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                            {{ __('admin.company_name') }}
                        </label>
                        <input type="text" wire:model="company_name"
                               class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs">
                        @error('company_name') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Domain -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                            {{ __('admin.domain') }}
                        </label>
                        <input type="text" wire:model="domain"
                               class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white font-mono focus:border-orange-500 focus:outline-none text-xs">
                        @error('domain') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Plan -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                            {{ __('admin.assigned_plan') }}
                        </label>
                        <select wire:model="plan_id"
                                class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white focus:border-orange-500 focus:outline-none text-xs">
                            <option value="">-- {{ __('admin.no_plan') }} --</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">
                                    {{ $plan->name }} — {{ number_format((float) $plan->price_egp, 0) }} ج.م (${{ number_format((float) $plan->price_usd, 0) }} USD)
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/[0.08]">
                        <button type="button" wire:click="closeModals" class="px-4 py-2 rounded-xl text-zinc-400 hover:text-white font-bold">
                            {{ __('admin.cancel') }}
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white font-black shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all">
                            {{ __('admin.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
