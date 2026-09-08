<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.dashboard') }}
    </x-slot:header>

    @livewire('admin.dashboard-overview')
</x-admin::layouts.master>
