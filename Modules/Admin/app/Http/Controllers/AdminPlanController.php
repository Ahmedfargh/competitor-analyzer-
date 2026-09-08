<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Admin\Http\Requests\Contracts\StorePlanRequestInterface;
use Modules\Admin\Services\Contracts\SubscriptionPlanServiceInterface;

class AdminPlanController extends Controller
{
    public function __construct(
        protected SubscriptionPlanServiceInterface $planService
    ) {}

    /**
     * Display a listing of subscription plans (EGP and USD).
     */
    public function index(): View
    {
        $plans = $this->planService->getPlansInEgp();

        return view('admin::plans.index', [
            'plans' => $plans,
        ]);
    }

    /**
     * Show the form for creating a new subscription plan.
     */
    public function create(): View
    {
        return view('admin::plans.create');
    }

    /**
     * Store a newly created plan using StorePlanRequestInterface.
     */
    public function store(StorePlanRequestInterface $request): RedirectResponse
    {
        $dto = $request->toDTO();
        $this->planService->savePlan($dto);

        return redirect()->route('admin.plans.index')
            ->with('success', __('admin.plan_created_successfully'));
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(int $id): View
    {
        $plan = $this->planService->findPlan($id);
        if (! $plan) {
            abort(404, 'Subscription plan not found');
        }

        return view('admin::plans.edit', [
            'plan' => $plan,
        ]);
    }

    /**
     * Update the specified plan using StorePlanRequestInterface.
     */
    public function update(StorePlanRequestInterface $request, int $id): RedirectResponse
    {
        $dto = $request->toDTO();
        $this->planService->savePlan($dto);

        return redirect()->route('admin.plans.index')
            ->with('success', __('admin.plan_updated_successfully'));
    }

    /**
     * Remove the specified plan.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->planService->deletePlan($id);

        return redirect()->route('admin.plans.index')
            ->with('success', __('admin.plan_deleted_successfully'));
    }
}
