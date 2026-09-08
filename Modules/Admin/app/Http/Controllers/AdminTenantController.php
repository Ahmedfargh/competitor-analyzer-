<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Admin\Http\Requests\Contracts\StoreTenantRequestInterface;
use Modules\Admin\Http\Requests\Contracts\UpdateTenantRequestInterface;
use Modules\Admin\Services\Contracts\SubscriptionPlanServiceInterface;
use Modules\Admin\Services\Contracts\TenantServiceInterface;

class AdminTenantController extends Controller
{
    public function __construct(
        protected TenantServiceInterface $tenantService,
        protected SubscriptionPlanServiceInterface $planService
    ) {}

    /**
     * Display a listing of tenants.
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->query('search'),
            'plan_id' => $request->query('plan_id'),
        ];

        $tenants = $this->tenantService->listTenants($filters, 10);
        $plans = $this->planService->getAllPlans();

        return view('admin::tenants.index', [
            'tenants' => $tenants,
            'plans' => $plans,
            'filters' => $filters,
        ]);
    }

    /**
     * Show form to provision a new tenant.
     */
    public function create(): View
    {
        $plans = $this->planService->getAllPlans();

        return view('admin::tenants.create', [
            'plans' => $plans,
        ]);
    }

    /**
     * Provision a new tenant using StoreTenantRequestInterface.
     */
    public function store(StoreTenantRequestInterface $request): RedirectResponse
    {
        $dto = $request->toDTO();
        $tenant = $this->tenantService->provisionTenant($dto);

        return redirect()->route('admin.tenants.show', $tenant->id)
            ->with('success', __('admin.tenant_provisioned_successfully', ['id' => $tenant->id]));
    }

    /**
     * Show and browse tenant's isolated data and schemas.
     */
    public function show(string $id): View
    {
        $tenantData = $this->tenantService->browseTenantData($id);

        return view('admin::tenants.show', [
            'tenant' => $tenantData['tenant'],
            'domains' => $tenantData['domains'],
            'databaseStatus' => $tenantData['database_status'],
            'tables' => $tenantData['tables'],
            'recentUsers' => $tenantData['recent_users'],
            'counts' => $tenantData['counts'],
            'error' => $tenantData['error'],
        ]);
    }

    /**
     * Show form to edit tenant configuration.
     */
    public function edit(string $id): View
    {
        $tenant = $this->tenantService->getTenant($id);
        $plans = $this->planService->getAllPlans();

        return view('admin::tenants.edit', [
            'tenant' => $tenant,
            'plans' => $plans,
        ]);
    }

    /**
     * Update tenant configuration using UpdateTenantRequestInterface.
     */
    public function update(UpdateTenantRequestInterface $request, string $id): RedirectResponse
    {
        $dto = $request->toDTO();
        $this->tenantService->updateTenant($id, $dto);

        return redirect()->route('admin.tenants.show', $id)
            ->with('success', __('admin.tenant_updated_successfully', ['id' => $id]));
    }

    /**
     * Deprovision and delete a tenant.
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->tenantService->deprovisionTenant($id);

        return redirect()->route('admin.tenants.index')
            ->with('success', __('admin.tenant_deleted_successfully', ['id' => $id]));
    }
}
