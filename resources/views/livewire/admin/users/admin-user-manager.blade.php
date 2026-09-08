<div>
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ __('admin.system_admins') }}</h1>
            <p class="text-xs text-zinc-400 mt-1">{{ __('admin.system_admins_desc') }}</p>
        </div>

        <button wire:click="openCreateModal"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white text-xs font-bold shadow-[0_0_20px_rgba(249,115,22,0.35)] transition-all transform hover:-translate-y-0.5 flex items-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>{{ __('admin.add_admin_user') }}</span>
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

    @error('user')
        <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-semibold">
            {{ $message }}
        </div>
    @enderror

    <!-- Filters & Search Bar -->
    <div class="card-luxury rounded-2xl p-4 mb-6 flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div class="relative w-full sm:w-80">
            <svg class="w-4 h-4 text-zinc-500 absolute start-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('admin.search_admins_placeholder') }}"
                   class="w-full ps-10 pe-4 py-2 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-500 focus:outline-none focus:border-orange-500 transition-colors">
        </div>

        <div class="w-full sm:w-60 flex items-center gap-2">
            <select wire:model.live="roleFilter"
                    class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs focus:outline-none focus:border-orange-500 transition-colors">
                <option value="">{{ __('admin.all_roles') }}</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card-luxury rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs text-zinc-300">
                <thead>
                    <tr class="bg-zinc-950/80 border-b border-white/[0.06] text-zinc-400 uppercase font-mono text-[10px]">
                        <th class="px-6 py-4 text-start">ID</th>
                        <th class="px-6 py-4 text-start">{{ __('admin.admin_user') }}</th>
                        <th class="px-6 py-4 text-start">{{ __('admin.roles') }}</th>
                        <th class="px-6 py-4 text-start">{{ __('admin.created_at') }}</th>
                        <th class="px-6 py-4 text-end">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @forelse ($users as $user)
                        <tr class="hover:bg-white/[0.02] transition-colors" wire:key="admin-user-{{ $user->id }}">
                            <td class="px-6 py-4 font-mono text-orange-400">#{{ $user->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-orange-500/20 to-amber-500/20 border border-orange-500/30 flex items-center justify-center text-orange-400 font-bold text-xs uppercase shadow-sm">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-sm flex items-center gap-2">
                                            <span>{{ $user->name }}</span>
                                            @if ($user->id === auth('admin')->id())
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-mono font-bold bg-emerald-500/15 border border-emerald-500/30 text-emerald-400">YOU</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-zinc-500 font-mono">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse ($user->roles as $role)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-orange-500/10 border border-orange-500/25 text-orange-400">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-zinc-600 text-[11px] italic">{{ __('admin.no_roles') }}</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-500 font-mono text-[11px]">
                                {{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $user->id }})"
                                            class="p-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white transition-colors"
                                            title="{{ __('admin.edit') }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @if ($user->id !== auth('admin')->id())
                                        <button wire:confirm="{{ __('admin.confirm_delete_admin_user') }}"
                                                wire:click="deleteUser({{ $user->id }})"
                                                class="p-2 rounded-lg bg-zinc-900 hover:bg-red-500/20 text-zinc-400 hover:text-red-400 transition-colors"
                                                title="{{ __('admin.delete') }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-500">
                                {{ __('admin.no_admin_users_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="p-4 border-t border-white/[0.06]">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE / EDIT MODAL -->
    @if ($showCreateModal || $showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div class="card-luxury rounded-3xl p-7 w-full max-w-lg shadow-2xl border border-white/10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-white/[0.08] mb-5">
                    <h3 class="text-lg font-black text-white tracking-tight">
                        {{ $showEditModal ? __('admin.edit_admin_user') : __('admin.add_admin_user') }}
                    </h3>
                    <button wire:click="closeModal" class="text-zinc-500 hover:text-white">✕</button>
                </div>

                <form wire:submit.prevent="{{ $showEditModal ? 'updateUser' : 'createUser' }}" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">{{ __('admin.name') }}</label>
                        <input type="text" wire:model="name"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-600 focus:outline-none focus:border-orange-500">
                        @error('name') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">{{ __('admin.email') }}</label>
                        <input type="email" wire:model="email"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-600 focus:outline-none focus:border-orange-500">
                        @error('email') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">
                            {{ __('admin.password') }}
                            @if ($showEditModal) <span class="text-zinc-500 font-normal">({{ __('admin.leave_blank_to_keep') }})</span> @endif
                        </label>
                        <input type="password" wire:model="password"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-600 focus:outline-none focus:border-orange-500">
                        @error('password') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">{{ __('admin.password_confirmation') }}</label>
                        <input type="password" wire:model="password_confirmation"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-zinc-950 border border-white/10 text-white text-xs placeholder-zinc-600 focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- Roles Selection -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-2">{{ __('admin.assign_roles') }}</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            @foreach ($roles as $role)
                                <label class="flex items-center gap-2 p-2.5 rounded-xl bg-zinc-950/80 border border-white/[0.06] hover:border-orange-500/30 cursor-pointer transition-colors">
                                    <input type="checkbox" wire:model="selectedRoles" value="{{ $role->name }}"
                                           class="rounded bg-zinc-900 border-white/20 text-orange-500 focus:ring-0">
                                    <span class="text-xs text-white font-medium">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/[0.08]">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-semibold transition-colors">
                            {{ __('admin.cancel') }}
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white text-xs font-bold shadow-[0_0_15px_rgba(249,115,22,0.35)] transition-all">
                            {{ $showEditModal ? __('admin.save_changes') : __('admin.create_user') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
