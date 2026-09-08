<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Services\Contracts\SubscriptionPlanServiceInterface;
use Modules\Admin\Services\Contracts\TenantServiceInterface;

class DashboardOverview extends Component
{
    public function render(
        TenantServiceInterface $tenantService,
        SubscriptionPlanServiceInterface $planService,
        ActivityLogServiceInterface $activityLogService
    ) {
        $tenantStats = $tenantService->getTenantOverviewStats();
        $recentTenants = $tenantService->listTenants([], 5);
        $plans = $planService->getAllPlans();
        $recentLogs = $activityLogService->getRecentLogs(8);

        return view('livewire.admin.dashboard-overview', [
            'tenantStats' => $tenantStats,
            'recentTenants' => $recentTenants,
            'plans' => $plans,
            'recentLogs' => $recentLogs,
        ]);
    }
}
