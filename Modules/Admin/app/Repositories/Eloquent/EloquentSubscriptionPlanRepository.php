<?php

namespace Modules\Admin\Repositories\Eloquent;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\SubscriptionPlanDTOInterface;
use Modules\Admin\Repositories\Contracts\SubscriptionPlanRepositoryInterface;

class EloquentSubscriptionPlanRepository implements SubscriptionPlanRepositoryInterface
{
    /**
     * Get all active subscription plans ordered by sort order.
     */
    public function all(): Collection
    {
        return SubscriptionPlan::orderBy('sort_order')->get();
    }

    /**
     * Find plan by ID.
     */
    public function findById(int $id): ?SubscriptionPlan
    {
        return SubscriptionPlan::find($id);
    }

    /**
     * Find plan by slug identifier.
     */
    public function findBySlug(string $slug): ?SubscriptionPlan
    {
        return SubscriptionPlan::where('slug', $slug)->first();
    }

    /**
     * Create or update a subscription plan.
     */
    public function createOrUpdate(SubscriptionPlanDTOInterface $dto): SubscriptionPlan
    {
        return SubscriptionPlan::updateOrCreate(
            ['slug' => $dto->getSlug()],
            $dto->toArray()
        );
    }

    /**
     * Delete a subscription plan.
     */
    public function delete(int $id): bool
    {
        $plan = SubscriptionPlan::find($id);
        if (! $plan) {
            return false;
        }

        return (bool) $plan->delete();
    }
}
