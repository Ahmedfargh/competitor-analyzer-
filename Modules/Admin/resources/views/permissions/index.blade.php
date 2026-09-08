<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.permissions_management') }}
    </x-slot:header>

    @livewire('admin.permissions.permission-manager')
</x-admin::layouts.master>
