<?php

namespace Modules\Admin\Observers;

use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Spatie\Permission\Models\Permission;

class PermissionObserver
{
    public function __construct(
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission): void
    {
        $this->activityLogService->log(
            action: 'permission.created',
            description: "Permission '{$permission->name}' created for guard '{$permission->guard_name}'.",
            subjectType: Permission::class,
            subjectId: (string) $permission->id,
            properties: ['guard_name' => $permission->guard_name]
        );
    }

    /**
     * Handle the Permission "deleted" event.
     */
    public function deleted(Permission $permission): void
    {
        $this->activityLogService->log(
            action: 'permission.deleted',
            description: "Permission '{$permission->name}' was removed.",
            subjectType: Permission::class,
            subjectId: (string) $permission->id
        );
    }
}
