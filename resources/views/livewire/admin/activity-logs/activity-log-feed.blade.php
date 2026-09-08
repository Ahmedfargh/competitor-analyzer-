<div wire:poll.10s>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ __('admin.activity_logs') }}</h1>
            <p class="text-xs text-zinc-400 mt-1">{{ __('admin.activity_logs_desc') }}</p>
        </div>

        <div class="flex items-center gap-2 text-xs font-mono text-zinc-500">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Live Sync Active (10s)</span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card-luxury rounded-2xl p-4 mb-6">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search logs by description, IP, action..."
                       class="w-full ps-10 pe-4 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white placeholder-zinc-500 text-xs focus:outline-none focus:border-orange-500">
                <svg class="w-4 h-4 text-zinc-500 absolute start-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <select wire:model.live="actionFilter"
                    class="w-full sm:w-56 px-3 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-zinc-300 text-xs focus:outline-none focus:border-orange-500">
                <option value="">All Action Events</option>
                @foreach ($actions as $act)
                    <option value="{{ $act }}">{{ $act }}</option>
                @endforeach
            </select>

            @if (!empty($search) || !empty($actionFilter))
                <button type="button" wire:click="$set('search', ''); $set('actionFilter', '');"
                        class="text-xs text-orange-400 hover:text-orange-300 px-2 font-bold">
                    Reset
                </button>
            @endif
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card-luxury rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm text-zinc-300">
                <thead class="text-xs uppercase bg-zinc-950/80 text-zinc-400 border-b border-white/[0.06] font-mono text-[10px]">
                    <tr>
                        <th class="px-6 py-4 text-start">{{ __('admin.action') }}</th>
                        <th class="px-6 py-4 text-start">{{ __('admin.description') }}</th>
                        <th class="px-6 py-4 text-start">{{ __('admin.operator') }}</th>
                        <th class="px-6 py-4 text-start">{{ __('admin.ip_address') }}</th>
                        <th class="px-6 py-4 text-end">{{ __('admin.timestamp') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-white/[0.02] transition-colors" wire:key="log-feed-{{ $log->id }}">
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 max-w-md">
                                <p dir="auto" class="text-sm font-semibold text-white leading-relaxed font-sans">{{ $log->description }}</p>
                                @if (!empty($log->properties))
                                    <details class="mt-1.5">
                                        <summary class="text-[11px] text-zinc-500 hover:text-orange-400 cursor-pointer font-mono">
                                            Payload details
                                        </summary>
                                        <pre class="mt-1.5 p-2 rounded-xl bg-zinc-950 text-[10px] text-zinc-400 font-mono overflow-x-auto">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                    </details>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-zinc-300">
                                @if ($log->admin)
                                    <span class="font-bold text-white block">{{ $log->admin->name }}</span>
                                    <span class="text-[10px] text-zinc-500 font-mono">{{ $log->admin->email }}</span>
                                @else
                                    <span class="text-zinc-500 italic">{{ __('admin.system') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-zinc-400">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-end text-xs text-zinc-400 whitespace-nowrap">
                                <span class="block text-zinc-300 font-mono">{{ $log->created_at->format('M d, H:i') }}</span>
                                <span class="text-[10px] text-zinc-500">{{ $log->created_at->diffForHumans() }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-500 text-sm">
                                {{ __('admin.no_logs') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="p-4 border-t border-white/[0.06]">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
