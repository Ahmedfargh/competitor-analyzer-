<?php

namespace Modules\Admin\Services;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\SubscriptionPlanDTOInterface;
use Modules\Admin\Repositories\Contracts\SubscriptionPlanRepositoryInterface;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Services\Contracts\SubscriptionPlanServiceInterface;

class SubscriptionPlanService implements SubscriptionPlanServiceInterface
{
    public function __construct(
        protected SubscriptionPlanRepositoryInterface $planRepo,
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Get all active subscription plans.
     */
    public function getAllPlans(): Collection
    {
        return $this->planRepo->all();
    }

    /**
     * Get plans formatted specifically for EGP currency displays.
     */
    public function getPlansInEgp(): Collection
    {
        return $this->planRepo->all()->map(function (SubscriptionPlan $plan) {
            $plan->setAttribute('display_price', number_format((float) $plan->price_egp, 0).' ج.م');
            $plan->setAttribute('currency_symbol', 'EGP');

            return $plan;
        });
    }

    /**
     * Find plan by ID.
     */
    public function findPlan(int $id): ?SubscriptionPlan
    {
        return $this->planRepo->findById($id);
    }

    /**
     * Save or update a subscription plan.
     */
    public function savePlan(SubscriptionPlanDTOInterface $dto): SubscriptionPlan
    {
        return $this->planRepo->createOrUpdate($dto);
    }

    /**
     * Delete a subscription plan.
     */
    public function deletePlan(int $id): bool
    {
        return $this->planRepo->delete($id);
    }
}
