<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.system_admins') }}
    </x-slot:header>

    @livewire('admin.users.admin-user-manager')
</x-admin::layouts.master>
