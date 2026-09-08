<?php

namespace App\Livewire\Admin\Tenants;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Admin\DTOs\Tenant\CreateTenantDTO;
use Modules\Admin\DTOs\Tenant\UpdateTenantDTO;
use Modules\Admin\Services\Contracts\SubscriptionPlanServiceInterface;
use Modules\Admin\Services\Contracts\TenantServiceInterface;

class TenantManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $planFilter = '';

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public string $editingTenantId = '';

    #[Validate('required|string|alpha_dash|min:3|max:50')]
    public string $tenant_id = '';

    #[Validate('required|string|min:2|max:100')]
    public string $company_name = '';

    #[Validate('required|string|min:3|max:100')]
    public string $domain = '';

    public ?int $plan_id = null;

    public ?string $statusMessage = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPlanFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['tenant_id', 'company_name', 'domain', 'plan_id', 'editingTenantId']);
        $this->resetValidation();
        $this->showCreateModal = true;
    }

    public function closeModals(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetValidation();
    }

    public function provisionTenant(TenantServiceInterface $tenantService): void
    {
        $this->validate([
            'tenant_id' => ['required', 'string', 'alpha_dash', 'min:3', 'max:50', 'unique:tenants,id'],
            'company_name' => ['required', 'string', 'min:2', 'max:100'],
            'domain' => ['required', 'string', 'min:3', 'max:100', 'unique:domains,domain'],
            'plan_id' => ['nullable', 'integer', 'exists:subscription_plans,id'],
        ]);

        $rawDomain = trim($this->domain);
        $rawDomain = preg_replace('#^https?://#i', '', $rawDomain);
        $rawDomain = explode('/', $rawDomain)[0];
        $rawDomain = explode(':', $rawDomain)[0];
        $rawDomain = strtolower(trim($rawDomain));
        if (! str_contains($rawDomain, '.')) {
            $centralHost = request()->getHost() ?: 'localhost';
            $rawDomain = "{$rawDomain}.{$centralHost}";
        }

        $dto = new CreateTenantDTO(
            tenantId: $this->tenant_id,
            companyName: $this->company_name,
            domain: $rawDomain,
            planId: $this->plan_id,
            metadata: [
                'created_by' => auth('admin')->id(),
                'ip' => request()->ip(),
            ]
        );

        $tenantService->provisionTenant($dto);

        $this->statusMessage = __('admin.tenant_provisioned_successfully', ['id' => $this->tenant_id]);
        $this->showCreateModal = false;
        $this->reset(['tenant_id', 'company_name', 'domain', 'plan_id']);
    }

    public function editTenant(string $id, TenantServiceInterface $tenantService): void
    {
        $tenant = $tenantService->getTenant($id);
        $this->editingTenantId = $tenant->id;
        $this->company_name = $tenant->company_name;
        $this->domain = $tenant->primary_domain ?? '';
        $this->plan_id = $tenant->plan_id ?? null;

        $this->resetValidation();
        $this->showEditModal = true;
    }

    public function saveEditedTenant(TenantServiceInterface $tenantService): void
    {
        $this->validate([
            'company_name' => ['required', 'string', 'min:2', 'max:100'],
            'domain' => ['required', 'string', 'min:3', 'max:100'],
            'plan_id' => ['nullable', 'integer', 'exists:subscription_plans,id'],
        ]);

        $rawDomain = trim($this->domain);
        $rawDomain = preg_replace('#^https?://#i', '', $rawDomain);
        $rawDomain = explode('/', $rawDomain)[0];
        $rawDomain = explode(':', $rawDomain)[0];
        $rawDomain = strtolower(trim($rawDomain));
        if (! str_contains($rawDomain, '.')) {
            $centralHost = request()->getHost() ?: 'localhost';
            $rawDomain = "{$rawDomain}.{$centralHost}";
        }

        $dto = new UpdateTenantDTO(
            companyName: $this->company_name,
            domain: $rawDomain,
            planId: $this->plan_id,
            metadata: [
                'updated_by' => auth('admin')->id(),
            ]
        );

        $tenantService->updateTenant($this->editingTenantId, $dto);

        $this->statusMessage = __('admin.tenant_updated_successfully', ['id' => $this->editingTenantId]);
        $this->showEditModal = false;
    }

    public function deleteTenant(string $id, TenantServiceInterface $tenantService): void
    {
        $tenantService->deprovisionTenant($id);
        $this->statusMessage = __('admin.tenant_deleted_successfully', ['id' => $id]);
    }

    public function render(TenantServiceInterface $tenantService, SubscriptionPlanServiceInterface $planService)
    {
        $filters = [
            'search' => $this->search,
            'plan_id' => $this->planFilter,
        ];

        $tenants = $tenantService->listTenants($filters, 10);
        $plans = $planService->getAllPlans();

        return view('livewire.admin.tenants.tenant-manager', [
            'tenants' => $tenants,
            'plans' => $plans,
        ]);
    }
}
