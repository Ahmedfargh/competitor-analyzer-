<div>
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ __('admin.roles_management') }}</h1>
            <p class="text-xs text-zinc-400 mt-1">{{ __('admin.roles_desc') }}</p>
        </div>

        <button wire:click="openCreateModal"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white text-xs font-bold shadow-[0_0_20px_rgba(249,115,22,0.35)] transition-all transform hover:-translate-y-0.5 flex items-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>{{ __('admin.add_role') }}</span>
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

    @error('role')
        <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-semibold">
            {{ $message }}
        </div>
    @enderror

    <!-- Roles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse ($roles as $role)
            <div class="card-luxury rounded-3xl p-6 relative group" wire:key="role-card-{{ $role->id }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-orange-500/15 border border-orange-500/30 flex items-center justify-center text-orange-400 shadow-[0_0_15px_rgba(249,115,22,0.2)]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-base tracking-tight">{{ $role->name }}</h3>
                            <span class="text-[10px] font-mono text-zinc-500 uppercase">{{ $role->guard_name }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                        <button wire:click="openEditModal({{ $role->id }})"
                                class="p-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white transition-colors"
                                title="{{ __('admin.edit') }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        @if ($role->name !== 'super_admin')
                            <button wire:confirm="{{ __('admin.confirm_delete_role') }}"
                                    wire:click="deleteRole({{ $role->id }})"
                                    class="p-2 rounded-lg bg-zinc-900 hover:bg-red-500/20 text-zinc-400 hover:text-red-400 transition-colors"
                                    title="{{ __('admin.delete') }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 py-3 border-y border-white/[0.06] my-4 text-center">
                    <div>
                        <span class="text-[10px] text-zinc-500 font-mono uppercase block">{{ __('admin.members') }}</span>
                        <span class="text-xl font-bold text-white font-mono">{{ $role->users_count ?? 0 }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-zinc-500 font-mono uppercase block">{{ __('admin.permissions') }}</span>
                        <span class="text-xl font-bold text-orange-400 font-mono">{{ $role->permissions->count() }}</span>
                    </div>
                </div>

                <!-- Sample permissions chips -->
                <div class="flex flex-wrap gap-1.5 max-h-24 overflow-hidden">
                    @forelse ($role->permissions->take(6) as $perm)
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-zinc-950 border border-white/[0.06] text-zinc-300">
                            {{ $perm->name }}
                        </span>
                    @empty
                        <span class="text-zinc-600 text-[11px] italic">{{ __('admin.all_permissions_or_none') }}</span>
                    @endforelse
                    @if ($role->permissions->count() > 6)
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-orange-500/10 text-orange-400 font-bold">
                            +{{ $role->permissions->count() - 6 }} more
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full card-luxury rounded-3xl p-12 text-center text-zinc-500">
                {{ __('admin.no_roles_found') }}
            </div>
        @endforelse
    </div>

    <!-- CREATE / EDIT ROLE MODAL -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div class="card-luxury rounded-3xl p-7 w-full max-w-2xl shadow-2xl border border-white/10 max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between pb-4 border-b border-white/[0.08] mb-5 shrink-0">
                    <h3 class="text-lg font-black text-white tracking-tight">
                        {{ $editingRoleId ? __('admin.edit_role') : __('admin.add_role') }}
                    </h3>
                    <button wire:click="closeModal" class="text-zinc-500 hover:text-white">✕</button>
                </div>

                <form wire:submit.prevent="saveRole" class="flex flex-col flex-1 overflow-hidden">
                    <div class="space-y-5 overflow-y-auto pe-2 flex-1">
                        <!-- Role Name -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">{{ __('admin.role_name') }}</label>
                            <input type="text" wire:model="name" placeholder="e.g. support_manager, billing_admin"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-600 focus:outline-none focus:border-orange-500">
                            @error('name') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Permissions Matrix -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-xs font-semibold text-zinc-300">{{ __('admin.assign_permissions') }}</label>
                                <span class="text-[11px] text-orange-400 font-mono font-bold">
                                    {{ count($selectedPermissions) }} {{ __('admin.selected') }}
                                </span>
                            </div>

                            <div class="space-y-4">
                                @foreach ($groupedPermissions as $group => $perms)
                                    <div class="p-4 rounded-2xl bg-zinc-950/80 border border-white/[0.06]">
                                        <div class="text-[11px] font-bold uppercase tracking-wider text-orange-400 font-mono mb-2.5 flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span>
                                            <span>{{ strtoupper($group) }}</span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach ($perms as $p)
                                                <label class="flex items-center gap-2.5 p-2 rounded-xl bg-zinc-900/60 hover:bg-zinc-900 border border-white/[0.04] hover:border-orange-500/30 cursor-pointer transition-colors">
                                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $p->name }}"
                                                           class="rounded bg-zinc-950 border-white/20 text-orange-500 focus:ring-0">
                                                    <span class="text-xs text-zinc-300 font-mono">{{ $p->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/[0.08] mt-4 shrink-0">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-semibold transition-colors">
                            {{ __('admin.cancel') }}
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white text-xs font-bold shadow-[0_0_15px_rgba(249,115,22,0.35)] transition-all">
                            {{ __('admin.save_role') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
