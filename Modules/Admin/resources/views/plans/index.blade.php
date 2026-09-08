<x-admin::layouts.master>
    <x-slot:header>
        {{ __('admin.plans') }}
    </x-slot:header>

    @livewire('admin.plans.plan-manager')
</x-admin::layouts.master>
