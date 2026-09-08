<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.tenants') }}
    </x-slot:header>

    @livewire('admin.tenants.tenant-manager')
</x-admin::layouts.master>
