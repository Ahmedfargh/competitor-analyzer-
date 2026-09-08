<div wire:poll.15s>
    <!-- EXECUTIVE WELCOME BANNER -->
    <div class="card-luxury rounded-3xl p-7 sm:p-9 mb-8 relative overflow-hidden">
        <!-- Ambient Glowing Background Arc -->
        <div class="absolute -top-24 right-1/4 w-[500px] h-[300px] bg-gradient-to-b from-orange-500/20 via-amber-500/5 to-transparent rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 left-10 w-60 h-60 bg-orange-600/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-500/10 border border-orange-500/30 text-orange-400 text-xs font-bold mb-3.5 shadow-[0_0_15px_rgba(249,115,22,0.15)]">
                    <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></span>
                    <span>{{ __('admin.super_admin') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight">
                    {{ __('admin.welcome_back') }}، 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-amber-300">
                        {{ auth('admin')->user()->name ?? 'Administrator' }}
                    </span>
                </h1>
                <p class="text-xs sm:text-sm text-zinc-400 mt-2 leading-relaxed">
                    {{ __('admin.tenants_desc') }}
                </p>
            </div>

            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('admin.tenants.index') }}"
                   class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-orange-500 via-orange-600 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white font-extrabold text-xs sm:text-sm shadow-[0_0_25px_rgba(249,115,22,0.45)] hover:shadow-[0_0_35px_rgba(249,115,22,0.7)] transition-all transform hover:-translate-y-0.5 flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>{{ __('admin.provision_tenant') }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 LUXURY KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Card 1: Active Tenants -->
        <div class="card-luxury rounded-3xl p-6 relative group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('admin.kpi_total_tenants') }}</span>
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-orange-500/20 to-amber-500/10 border border-orange-500/30 flex items-center justify-center text-orange-400 shadow-[0_0_15px_rgba(249,115,22,0.15)] group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl sm:text-4xl font-black text-white font-mono mt-4 tracking-tight group-hover:text-orange-400 transition-colors">
                {{ $tenantStats['total_tenants'] ?? 0 }}
            </p>
            <div class="mt-3 flex items-center gap-2">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/25 text-emerald-400">
                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    +{{ $tenantStats['recent_tenants_30d'] ?? 0 }}
                </span>
                <span class="text-[11px] text-zinc-400">{{ __('admin.kpi_new_30d') }}</span>
            </div>
        </div>

        <!-- Card 2: Subscription Tiers -->
        <div class="card-luxury rounded-3xl p-6 relative group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('admin.kpi_total_plans') }}</span>
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-amber-500/20 to-orange-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 shadow-[0_0_15px_rgba(251,191,36,0.15)] group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl sm:text-4xl font-black text-white font-mono mt-4 tracking-tight group-hover:text-amber-400 transition-colors">
                {{ ($plans ?? collect())->count() }}
            </p>
            <div class="mt-3 flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-500/15 border border-orange-500/30 text-orange-300 font-mono">
                    EGP / USD
                </span>
                <span class="text-[11px] text-zinc-400">باقات تسعير نشطة</span>
            </div>
        </div>

        <!-- Card 3: Activity Stream -->
        <div class="card-luxury rounded-3xl p-6 relative group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('admin.activity_logs') }}</span>
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500/20 to-indigo-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400 shadow-[0_0_15px_rgba(59,130,246,0.15)] group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl sm:text-4xl font-black text-white font-mono mt-4 tracking-tight group-hover:text-blue-400 transition-colors">
                {{ ($recentLogs ?? collect())->count() }}
            </p>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-zinc-400">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-ping"></span>
                <span>سجلات نشاط مسجلة</span>
            </div>
        </div>

        <!-- Card 4: System Operational State -->
        <div class="card-luxury rounded-3xl p-6 relative group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">{{ __('admin.kpi_system_status') }}</span>
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-teal-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.15)] group-hover:scale-110 transition-transform">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                </div>
            </div>
            <p class="text-xl sm:text-2xl font-black text-emerald-400 font-mono mt-4 tracking-tight flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Online 100%
            </p>
            <div class="mt-3 text-[11px] text-zinc-500 font-mono">
                PHP 8.5 · Stancl v3 · Gemini AI
            </div>
        </div>
    </div>

    <!-- MAIN GRID: TENANTS MANAGEMENT TABLE & REAL-TIME ACTIVITY STREAM -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- TENANTS OVERVIEW TABLE (8 Cols) -->
        <div class="lg:col-span-8 card-luxury rounded-3xl p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/[0.06]">
                <div>
                    <h3 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                        <span>{{ __('admin.tenants') }}</span>
                    </h3>
                    <p class="text-xs text-zinc-400 mt-0.5">{{ __('admin.tenants_desc') }}</p>
                </div>
                <a href="{{ route('admin.tenants.index') }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 text-xs font-bold border border-orange-500/20 transition-all">
                    <span>{{ __('admin.view_all') }}</span>
                    <span class="text-sm">←</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs text-zinc-300">
                    <thead>
                        <tr class="bg-zinc-950/80 border border-white/[0.06] text-zinc-400 uppercase font-mono text-[10px] tracking-wider rounded-2xl">
                            <th class="py-3 px-4 text-start rounded-s-xl">{{ __('admin.tenant_id') }}</th>
                            <th class="py-3 px-4 text-start">{{ __('admin.company_name') }}</th>
                            <th class="py-3 px-4 text-start">{{ __('admin.domain') }}</th>
                            <th class="py-3 px-4 text-end rounded-e-xl">{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        @forelse ($recentTenants as $tenant)
                            <tr class="hover:bg-white/[0.02] transition-colors group" wire:key="recent-tenant-{{ $tenant->id }}">
                                <td class="py-4 px-4 font-mono font-bold text-orange-400">
                                    <span class="px-2 py-0.5 rounded-lg bg-orange-500/10 border border-orange-500/20">
                                        {{ $tenant->id }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 font-bold text-white text-sm">
                                    {{ $tenant->company_name }}
                                </td>
                                <td class="py-4 px-4 font-mono text-zinc-400 text-xs">
                                    {{ $tenant->primary_domain ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-4 text-end">
                                    <a href="{{ route('admin.tenants.show', $tenant->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-500/10 hover:bg-orange-500/25 text-orange-400 text-xs font-bold border border-orange-500/20 transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>{{ __('admin.view_data') }}</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-zinc-500 text-xs">
                                    <div class="w-12 h-12 rounded-2xl bg-zinc-950 border border-white/10 flex items-center justify-center mx-auto mb-3 text-zinc-600">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                        </svg>
                                    </div>
                                    <p class="font-medium text-zinc-400">{{ __('admin.no_tenants') }}</p>
                                    <a href="{{ route('admin.tenants.index') }}" class="inline-flex items-center gap-1 text-orange-400 hover:text-orange-300 font-bold mt-2 text-xs">
                                        <span>+ {{ __('admin.provision_tenant') }}</span>
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RECENT AUDIT LOGS (4 Cols) -->
        <div class="lg:col-span-4 card-luxury rounded-3xl p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/[0.06]">
                <div>
                    <h3 class="text-lg font-black text-white tracking-tight">{{ __('admin.activity_logs') }}</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">{{ __('admin.activity_logs_desc') }}</p>
                </div>
                <a href="{{ route('admin.activity-logs.index') }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 text-xs font-bold border border-orange-500/20 transition-all">
                    <span>{{ __('admin.view_all') }}</span>
                    <span class="text-sm">←</span>
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($recentLogs as $log)
                    <div class="p-3.5 rounded-2xl bg-zinc-950/80 border border-white/[0.06] hover:border-orange-500/30 transition-all text-xs" wire:key="recent-log-{{ $log->id }}">
                        <div class="flex items-center justify-between text-[11px] mb-1.5">
                            <span class="px-2.5 py-0.5 rounded-full bg-orange-500/15 border border-orange-500/30 text-orange-400 font-mono font-bold text-[10px]">
                                {{ $log->action }}
                            </span>
                            <span class="text-zinc-500 font-mono text-[10px]">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p dir="auto" class="text-zinc-300 leading-relaxed font-sans text-xs">
                            {{ $log->description }}
                        </p>
                        @if ($log->admin)
                            <div class="flex items-center gap-1.5 mt-2 pt-2 border-t border-white/[0.04] text-[10px] text-zinc-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span>
                                <span>{{ $log->admin->name }}</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center py-10 text-zinc-500 text-xs">{{ __('admin.no_logs') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
