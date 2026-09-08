<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.roles_management') }}
    </x-slot:header>

    @livewire('admin.roles.role-manager')
</x-admin::layouts.master>
