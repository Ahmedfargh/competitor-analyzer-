<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Services\Contracts\SubscriptionPlanServiceInterface;
use Modules\Admin\Services\Contracts\TenantServiceInterface;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected TenantServiceInterface $tenantService,
        protected SubscriptionPlanServiceInterface $planService,
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Display the Admin Central Overview Dashboard.
     */
    public function index(): View
    {
        $tenantStats = $this->tenantService->getTenantOverviewStats();
        $recentTenants = $this->tenantService->listTenants([], 5);
        $plans = $this->planService->getAllPlans();
        $recentLogs = $this->activityLogService->getRecentLogs(8);

        return view('admin::index', [
            'tenantStats' => $tenantStats,
            'recentTenants' => $recentTenants,
            'plans' => $plans,
            'recentLogs' => $recentLogs,
        ]);
    }
}
