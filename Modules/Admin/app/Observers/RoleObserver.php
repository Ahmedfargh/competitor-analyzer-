<?php

namespace Modules\Admin\Observers;

use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Spatie\Permission\Models\Role;

class RoleObserver
{
    public function __construct(
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        $this->activityLogService->log(
            action: 'role.created',
            description: "Role '{$role->name}' created.",
            subjectType: Role::class,
            subjectId: (string) $role->id,
            properties: ['guard_name' => $role->guard_name]
        );
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        $this->activityLogService->log(
            action: 'role.updated',
            description: "Role '{$role->name}' updated.",
            subjectType: Role::class,
            subjectId: (string) $role->id,
            properties: $role->getChanges()
        );
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        $this->activityLogService->log(
            action: 'role.deleted',
            description: "Role '{$role->name}' was removed.",
            subjectType: Role::class,
            subjectId: (string) $role->id
        );
    }
}
