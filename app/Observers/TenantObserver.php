<?php

namespace App\Observers;

use App\Models\Tenant;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;

class TenantObserver
{
    public function __construct(
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Handle the Tenant "created" event.
     */
    public function created(Tenant $tenant): void
    {
        $companyName = $tenant->company_name ?? $tenant->id;

        $this->activityLogService->log(
            action: 'tenant.provisioned',
            description: "Tenant '{$tenant->id}' ({$companyName}) was provisioned.",
            subjectType: Tenant::class,
            subjectId: (string) $tenant->id,
            properties: $tenant->toArray()
        );
    }

    /**
     * Handle the Tenant "updated" event.
     */
    public function updated(Tenant $tenant): void
    {
        $this->activityLogService->log(
            action: 'tenant.updated',
            description: "Tenant '{$tenant->id}' updated configuration.",
            subjectType: Tenant::class,
            subjectId: (string) $tenant->id,
            properties: $tenant->getChanges()
        );
    }

    /**
     * Handle the Tenant "deleted" event.
     */
    public function deleted(Tenant $tenant): void
    {
        $this->activityLogService->log(
            action: 'tenant.deprovisioned',
            description: "Tenant '{$tenant->id}' was purged and deprovisioned.",
            subjectType: Tenant::class,
            subjectId: (string) $tenant->id
        );
    }
}
