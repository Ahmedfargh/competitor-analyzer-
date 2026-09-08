<?php

namespace App\Observers;

use App\Models\SubscriptionPlan;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;

class SubscriptionPlanObserver
{
    public function __construct(
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Handle the SubscriptionPlan "created" event.
     */
    public function created(SubscriptionPlan $plan): void
    {
        $this->activityLogService->log(
            action: 'plan.saved',
            description: "Subscription plan '{$plan->name}' saved ({$plan->price_egp} EGP / {$plan->price_usd} USD).",
            subjectType: SubscriptionPlan::class,
            subjectId: (string) $plan->id,
            properties: $plan->toArray()
        );
    }

    /**
     * Handle the SubscriptionPlan "updated" event.
     */
    public function updated(SubscriptionPlan $plan): void
    {
        $this->activityLogService->log(
            action: 'plan.saved',
            description: "Subscription plan '{$plan->name}' updated ({$plan->price_egp} EGP / {$plan->price_usd} USD).",
            subjectType: SubscriptionPlan::class,
            subjectId: (string) $plan->id,
            properties: $plan->getChanges()
        );
    }

    /**
     * Handle the SubscriptionPlan "deleted" event.
     */
    public function deleted(SubscriptionPlan $plan): void
    {
        $this->activityLogService->log(
            action: 'plan.deleted',
            description: "Subscription plan '{$plan->name}' (ID: {$plan->id}) was removed.",
            subjectType: SubscriptionPlan::class,
            subjectId: (string) $plan->id
        );
    }
}
