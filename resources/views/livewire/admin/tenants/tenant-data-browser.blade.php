<div>
    <!-- Top Navigation & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.tenants.index') }}" class="text-xs text-zinc-400 hover:text-white flex items-center gap-1 mb-2">
                <span>←</span>
                <span>{{ __('admin.tenants') }}</span>
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $tenant->company_name }}</h1>
                <span class="px-3 py-1 rounded-xl text-xs font-mono font-bold bg-orange-500/10 text-orange-400 border border-orange-500/20">
                    {{ $tenant->id }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if ($tenant->primary_url)
                <a href="{{ $tenant->primary_url }}" target="_blank" rel="noopener noreferrer"
                   class="px-4 py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold flex items-center gap-1.5 transition-colors">
                    <span>Visit Tenant</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>

    <!-- Tenant Status & Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <!-- DB Status -->
        <div class="card-luxury rounded-3xl p-6">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('admin.database_status') }}</span>
            <div class="mt-3 flex items-center gap-2">
                @if ($databaseStatus === 'connected')
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-emerald-400 font-bold text-sm">Active & Partitioned</span>
                @else
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span class="text-amber-400 font-bold text-sm">Pending Migrations</span>
                @endif
            </div>
            @if ($error)
                <p class="text-[11px] text-red-400 mt-2 font-mono break-all">{{ $error }}</p>
            @endif
        </div>

        <!-- Tenant Users Count -->
        <div class="card-luxury rounded-3xl p-6">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('admin.tenant_users_count') }}</span>
            <p class="text-3xl font-black text-white mt-2 font-mono">{{ $counts['users'] ?? 0 }}</p>
            <p class="text-xs text-zinc-500 mt-1">Users in tenant-scoped database</p>
        </div>

        <!-- Assigned Plan -->
        <div class="card-luxury rounded-3xl p-6">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('admin.assigned_plan') }}</span>
            <p class="text-lg font-bold text-orange-400 mt-2">
                {{ $tenant->plan ? $tenant->plan->name : __('admin.no_plan') }}
            </p>
            @if ($tenant->plan)
                <p class="text-xs text-zinc-400 mt-1 font-mono">
                    {{ number_format((float) $tenant->plan->price_egp, 0) }} ج.م / {{ $tenant->plan->billing_period }}
                </p>
            @endif
        </div>
    <!-- PRODUCT PROFILE & COMPETITIVE INTELLIGENCE CARD -->
    <div class="card-luxury rounded-3xl p-6 sm:p-8 mb-8 border border-white/10 bg-gradient-to-br from-zinc-900/90 to-zinc-950/90">
        <div class="flex items-center justify-between gap-4 mb-4 pb-3 border-b border-white/[0.06]">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                <h3 class="text-base font-bold text-white">Product Profile & Competitive Intelligence</h3>
            </div>
            <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="px-3 py-1.5 rounded-xl bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 border border-orange-500/20 text-xs font-semibold transition-colors">
                Configure Profile →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div>
                <span class="text-zinc-500 font-semibold uppercase tracking-wider text-[10px] block">Product Solution</span>
                <p class="text-zinc-200 font-bold mt-0.5">{{ $tenant->product_profile['product_name'] ?? $tenant->company_name }}</p>
                <p class="text-zinc-400 mt-1 italic">{{ $tenant->product_profile['value_proposition'] ?? 'No value proposition configured yet.' }}</p>
            </div>
            <div>
                <span class="text-zinc-500 font-semibold uppercase tracking-wider text-[10px] block">Pricing & Target ICP</span>
                <p class="text-orange-400 font-mono mt-0.5">{{ $tenant->product_profile['pricing_summary'] ?? 'Pricing summary not specified' }}</p>
                <p class="text-zinc-400 mt-1">{{ $tenant->product_profile['target_icp'] ?? 'All market segments' }}</p>
            </div>
        </div>

        @if (! empty($tenant->product_profile['key_differentiators']))
            <div class="mt-4 pt-3 border-t border-white/[0.06]">
                <span class="text-zinc-500 font-semibold uppercase tracking-wider text-[10px] block mb-1.5">Key Differentiators & Moats</span>
                <div class="flex flex-wrap gap-1.5">
                    @foreach ($tenant->product_profile['key_differentiators'] as $diff)
                        <span class="px-2.5 py-1 rounded-lg bg-zinc-800 text-zinc-300 text-[11px] border border-white/5">
                            ✓ {{ $diff }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- DATA BROWSER SECTION -->
    <div class="space-y-8">
        <!-- RECENT USERS IN TENANT DB WITH LIVE SEARCH & MANAGEMENT -->
        <div class="card-luxury rounded-3xl p-6 sm:p-8">
            @if ($feedbackMessage)
                <div class="mb-6 p-4 rounded-2xl text-xs font-semibold flex items-center justify-between {{ $feedbackType === 'success' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                    <span>{{ $feedbackMessage }}</span>
                    <button type="button" wire:click="$set('feedbackMessage', null)" class="text-zinc-400 hover:text-white">✕</button>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-white/[0.06]">
                <div>
                    <h3 class="text-lg font-black text-white tracking-tight">{{ __('admin.recent_tenant_users') }}</h3>
                    <p class="text-xs text-zinc-400">Queried directly from tenant database schema</p>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="w-full sm:w-64">
                        <input type="text" wire:model.live.debounce.250ms="userSearch" placeholder="Filter users by name/email..."
                               class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-500 focus:outline-none focus:border-orange-500">
                    </div>
                    <button type="button" wire:click="openCreateUserModal"
                            class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold whitespace-nowrap shadow-lg shadow-orange-500/20 transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Add User</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs text-zinc-300">
                    <thead>
                        <tr class="bg-zinc-950/80 border border-white/[0.06] text-zinc-400 uppercase font-mono text-[10px]">
                            <th class="px-4 py-3 text-start rounded-s-xl">ID</th>
                            <th class="px-4 py-3 text-start">{{ __('admin.user_name') }}</th>
                            <th class="px-4 py-3 text-start">{{ __('admin.user_email') }}</th>
                            <th class="px-4 py-3 text-start">{{ __('admin.registered_at') }}</th>
                            <th class="px-4 py-3 text-end rounded-e-xl">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        @forelse ($recentUsers as $user)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-4 py-3 font-mono text-orange-400">{{ $user->id ?? '—' }}</td>
                                <td class="px-4 py-3 font-bold text-white text-sm">{{ $user->name ?? '—' }}</td>
                                <td class="px-4 py-3 font-mono text-zinc-400">{{ $user->email ?? '—' }}</td>
                                <td class="px-4 py-3 text-start text-zinc-500">{{ $user->created_at ?? '—' }}</td>
                                <td class="px-4 py-3 text-end">
                                    @if (! empty($user->id))
                                        <button type="button"
                                                wire:click="deleteTenantUser('{{ $user->id }}')"
                                                wire:confirm="Are you sure you want to remove user {{ $user->email }} from this tenant database?"
                                                class="px-2.5 py-1 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 text-[11px] font-semibold transition-colors">
                                            Delete
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-zinc-500 text-xs">
                                    {{ __('admin.no_users_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CREATE TENANT USER MODAL -->
        @if ($showCreateUserModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-fadeIn">
                <div class="relative w-full max-w-md p-6 sm:p-8 bg-zinc-900 border border-white/10 rounded-3xl shadow-2xl space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-white/[0.06]">
                        <div>
                            <h4 class="text-lg font-black text-white">Create Tenant User</h4>
                            <p class="text-xs text-zinc-400 mt-0.5">Directly provisioned in tenant partition</p>
                        </div>
                        <button type="button" wire:click="closeCreateUserModal" class="text-zinc-500 hover:text-white transition-colors">✕</button>
                    </div>

                    <form wire:submit="saveTenantUser" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Full Name</label>
                            <input type="text" wire:model="newUserName" placeholder="e.g. Jane Doe"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-500 focus:outline-none focus:border-orange-500">
                            @error('newUserName') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Email Address</label>
                            <input type="email" wire:model="newUserEmail" placeholder="user@company.com"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-500 focus:outline-none focus:border-orange-500">
                            @error('newUserEmail') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Password</label>
                            <input type="password" wire:model="newUserPassword" placeholder="Minimum 8 characters"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-500 focus:outline-none focus:border-orange-500">
                            @error('newUserPassword') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/[0.06]">
                            <button type="button" wire:click="closeCreateUserModal"
                                    class="px-4 py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-semibold transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold shadow-lg shadow-orange-500/25 transition-all">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- ISOLATED TABLES LIST -->
        @if (!empty($tables))
            <div class="card-luxury rounded-3xl p-6 sm:p-8">
                <div class="mb-4">
                    <h3 class="text-lg font-black text-white tracking-tight">{{ __('admin.isolated_tables') }}</h3>
                    <p class="text-xs text-zinc-400">Database schema tables detected in tenant partition</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($tables as $table)
                        <span class="px-3 py-1.5 rounded-xl bg-zinc-950 border border-white/10 text-xs font-mono text-orange-400/90 shadow-sm">
                            {{ is_string($table) ? $table : json_encode($table) }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
