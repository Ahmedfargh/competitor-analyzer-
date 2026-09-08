<?php

namespace Modules\Admin\Observers;

use Modules\Admin\Models\Admin;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;

class AdminObserver
{
    public function __construct(
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Handle the Admin "created" event.
     */
    public function created(Admin $admin): void
    {
        $this->activityLogService->log(
            action: 'admin_user.created',
            description: "System administrator '{$admin->name}' ({$admin->email}) created.",
            subjectType: Admin::class,
            subjectId: (string) $admin->id,
            properties: ['admin_id' => $admin->id, 'email' => $admin->email]
        );
    }

    /**
     * Handle the Admin "updated" event.
     */
    public function updated(Admin $admin): void
    {
        $this->activityLogService->log(
            action: 'admin_user.updated',
            description: "System administrator '{$admin->name}' ({$admin->email}) updated.",
            subjectType: Admin::class,
            subjectId: (string) $admin->id,
            properties: $admin->getChanges()
        );
    }

    /**
     * Handle the Admin "deleted" event.
     */
    public function deleted(Admin $admin): void
    {
        $this->activityLogService->log(
            action: 'admin_user.deleted',
            description: "System administrator '{$admin->name}' ({$admin->email}) deleted.",
            subjectType: Admin::class,
            subjectId: (string) $admin->id,
            properties: ['deleted_admin_id' => $admin->id]
        );
    }
}
