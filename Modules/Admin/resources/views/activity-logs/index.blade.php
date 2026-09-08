<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.activity_logs') }}
    </x-slot:header>

    @livewire('admin.activity-logs.activity-log-feed')
</x-admin::layouts.master>
