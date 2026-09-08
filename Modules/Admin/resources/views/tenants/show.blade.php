<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.browse_data_title') }}: {{ $tenant->company_name }}
    </x-slot:header>

    @livewire('admin.tenants.tenant-data-browser', ['tenantId' => $tenant->id])
</x-admin::layouts.master>
