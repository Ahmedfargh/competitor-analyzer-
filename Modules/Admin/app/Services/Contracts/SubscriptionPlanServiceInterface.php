<?php

namespace Modules\Admin\Services\Contracts;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\SubscriptionPlanDTOInterface;

interface SubscriptionPlanServiceInterface
{
    /**
     * Get all active subscription plans.
     */
    public function getAllPlans(): Collection;

    /**
     * Get plans formatted specifically for EGP currency displays.
     */
    public function getPlansInEgp(): Collection;

    /**
     * Find plan by ID.
     */
    public function findPlan(int $id): ?SubscriptionPlan;

    /**
     * Save or update a subscription plan.
     */
    public function savePlan(SubscriptionPlanDTOInterface $dto): SubscriptionPlan;

    /**
     * Delete a subscription plan.
     */
    public function deletePlan(int $id): bool;
}
