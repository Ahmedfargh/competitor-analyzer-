<?php

namespace Modules\Admin\Repositories\Contracts;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\SubscriptionPlanDTOInterface;

interface SubscriptionPlanRepositoryInterface
{
    /**
     * Get all active subscription plans ordered by sort order.
     */
    public function all(): Collection;

    /**
     * Find plan by ID.
     */
    public function findById(int $id): ?SubscriptionPlan;

    /**
     * Find plan by slug identifier.
     */
    public function findBySlug(string $slug): ?SubscriptionPlan;

    /**
     * Create or update a subscription plan.
     */
    public function createOrUpdate(SubscriptionPlanDTOInterface $dto): SubscriptionPlan;

    /**
     * Delete a subscription plan.
     */
    public function delete(int $id): bool;
}
