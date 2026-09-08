<?php

namespace App\Observers;

use App\Models\TenantFeatureCustomization;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Support\TenantLayerCustomizer;

class TenantFeatureCustomizationObserver
{
    public function __construct(
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Handle the TenantFeatureCustomization "created" event.
     */
    public function created(TenantFeatureCustomization $customization): void
    {
        TenantLayerCustomizer::clearTenantCache($customization->tenant_id);

        $this->activityLogService->log(
            action: 'tenant.feature_customized',
            description: "Tenant '{$customization->tenant_id}' mapped base class '{$customization->base_class}' to '{$customization->alternative_class}'.",
            subjectType: TenantFeatureCustomization::class,
            subjectId: (string) $customization->id,
            properties: $customization->toArray()
        );
    }

    /**
     * Handle the TenantFeatureCustomization "updated" event.
     */
    public function updated(TenantFeatureCustomization $customization): void
    {
        TenantLayerCustomizer::clearTenantCache($customization->tenant_id);

        $this->activityLogService->log(
            action: 'tenant.feature_updated',
            description: "Tenant '{$customization->tenant_id}' updated customization for '{$customization->base_class}'.",
            subjectType: TenantFeatureCustomization::class,
            subjectId: (string) $customization->id,
            properties: $customization->getChanges()
        );
    }

    /**
     * Handle the TenantFeatureCustomization "deleted" event.
     */
    public function deleted(TenantFeatureCustomization $customization): void
    {
        TenantLayerCustomizer::clearTenantCache($customization->tenant_id);

        $this->activityLogService->log(
            action: 'tenant.feature_removed',
            description: "Tenant '{$customization->tenant_id}' deleted customization for '{$customization->base_class}'.",
            subjectType: TenantFeatureCustomization::class,
            subjectId: (string) $customization->id
        );
    }
}
