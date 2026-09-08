<div>
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ __('admin.permissions_management') }}</h1>
            <p class="text-xs text-zinc-400 mt-1">{{ __('admin.permissions_desc') }}</p>
        </div>

        <button wire:click="openCreateModal"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white text-xs font-bold shadow-[0_0_20px_rgba(249,115,22,0.35)] transition-all transform hover:-translate-y-0.5 flex items-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>{{ __('admin.add_permission') }}</span>
        </button>
    </div>

    <!-- Feedback Alerts -->
    @if ($statusMessage)
        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold flex items-center justify-between shadow-[0_0_20px_rgba(16,185,129,0.15)]">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>{{ $statusMessage }}</span>
            </div>
            <button wire:click="$set('statusMessage', null)" class="text-emerald-400/60 hover:text-emerald-300">✕</button>
        </div>
    @endif

    <!-- Group Filter Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6">
        <button wire:click="$set('filterGroup', '')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-mono font-bold transition-all {{ $filterGroup === '' ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-zinc-900 text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
            {{ __('admin.all_groups') }}
        </button>
        @foreach ($allGroups as $g)
            <button wire:click="$set('filterGroup', '{{ $g }}')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-mono font-bold transition-all {{ $filterGroup === $g ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-zinc-900 text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                {{ strtoupper($g) }}
            </button>
        @endforeach
    </div>

    <!-- Grouped Permissions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        @forelse ($groupedPermissions as $group => $permissions)
            <div class="card-luxury rounded-3xl p-6" wire:key="perm-group-{{ $group }}">
                <div class="flex items-center justify-between pb-3.5 border-b border-white/[0.06] mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                        <h3 class="font-bold text-white text-base tracking-tight uppercase font-mono">{{ $group }}</h3>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono font-bold bg-orange-500/10 border border-orange-500/30 text-orange-400">
                        {{ count($permissions) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach ($permissions as $perm)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-zinc-950/80 border border-white/[0.04] hover:border-orange-500/30 group transition-all" wire:key="perm-{{ $perm->id }}">
                            <span class="text-xs font-mono text-zinc-300 group-hover:text-white transition-colors">
                                {{ $perm->name }}
                            </span>
                            <button wire:confirm="{{ __('admin.confirm_delete_permission') }}"
                                    wire:click="deletePermission({{ $perm->id }})"
                                    class="text-zinc-600 hover:text-red-400 p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                    title="{{ __('admin.delete') }}">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="col-span-full card-luxury rounded-3xl p-12 text-center text-zinc-500">
                {{ __('admin.no_permissions_found') }}
            </div>
        @endforelse
    </div>

    <!-- CREATE MODAL -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div class="card-luxury rounded-3xl p-7 w-full max-w-md shadow-2xl border border-white/10">
                <div class="flex items-center justify-between pb-4 border-b border-white/[0.08] mb-5">
                    <h3 class="text-lg font-black text-white tracking-tight">{{ __('admin.add_permission') }}</h3>
                    <button wire:click="closeModal" class="text-zinc-500 hover:text-white">✕</button>
                </div>

                <form wire:submit.prevent="createPermission" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">{{ __('admin.permission_name') }}</label>
                        <input type="text" wire:model="name" placeholder="e.g. tenants.create, plans.delete"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-600 focus:outline-none focus:border-orange-500 font-mono">
                        <p class="text-[11px] text-zinc-500 mt-1">Recommended format: <span class="font-mono text-orange-400">module.action</span> (e.g. <span class="font-mono">users.edit</span>)</p>
                        @error('name') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/[0.08]">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-semibold transition-colors">
                            {{ __('admin.cancel') }}
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white text-xs font-bold shadow-[0_0_15px_rgba(249,115,22,0.35)] transition-all">
                            {{ __('admin.create_permission') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
