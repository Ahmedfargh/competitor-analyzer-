<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Services\Contracts\SubscriptionPlanServiceInterface;
use Modules\Admin\Services\Contracts\TenantServiceInterface;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        TenantServiceInterface $tenantService,
        SubscriptionPlanServiceInterface $planService,
        ActivityLogServiceInterface $activityLogService
    ) {
        return view('admin::index', [
            'tenantStats' => $tenantService->getTenantOverviewStats(),
            'recentTenants' => $tenantService->listTenants([], 5),
            'plans' => $planService->getAllPlans(),
            'recentLogs' => $activityLogService->getRecentLogs(8),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
