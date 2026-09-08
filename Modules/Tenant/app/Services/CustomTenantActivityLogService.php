<?php

namespace Modules\Tenant\Services;

use App\Models\ActivityLog;
use Modules\Admin\Services\ActivityLogService;

class CustomTenantActivityLogService extends ActivityLogService
{
    /**
     * Record an audit or activity entry with tenant-customized enrichment.
     */
    public function log(
        string $action,
        string $description,
        ?string $subjectType = null,
        ?string $subjectId = null,
        ?array $properties = []
    ): ActivityLog {
        $enhancedProperties = array_merge($properties ?? [], [
            'tenant_customized' => true,
            'resolver_namespace' => static::class,
        ]);

        return parent::log(
            action: $action,
            description: "[Tenant Custom] {$description}",
            subjectType: $subjectType,
            subjectId: $subjectId,
            properties: $enhancedProperties
        );
    }
}
