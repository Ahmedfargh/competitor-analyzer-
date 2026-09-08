<?php

namespace App\Observers;

use App\Models\User;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;

class UserObserver
{
    public function __construct(
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;

        $this->activityLogService->log(
            action: 'tenant_user.created',
            description: "Tenant user '{$user->name}' ({$user->email}) was created".($tenantId ? " in tenant '{$tenantId}'" : '').'.',
            subjectType: User::class,
            subjectId: (string) $user->id,
            properties: [
                'name' => $user->name,
                'email' => $user->email,
                'tenant_id' => $tenantId,
            ]
        );
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;

        $this->activityLogService->log(
            action: 'tenant_user.updated',
            description: "Tenant user '{$user->name}' ({$user->email}) was updated".($tenantId ? " in tenant '{$tenantId}'" : '').'.',
            subjectType: User::class,
            subjectId: (string) $user->id,
            properties: array_merge($user->getChanges(), [
                'tenant_id' => $tenantId,
            ])
        );
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;

        $this->activityLogService->log(
            action: 'tenant_user.deleted',
            description: "Tenant user '{$user->name}' ({$user->email}) was removed".($tenantId ? " from tenant '{$tenantId}'" : '').'.',
            subjectType: User::class,
            subjectId: (string) $user->id,
            properties: [
                'name' => $user->name,
                'email' => $user->email,
                'tenant_id' => $tenantId,
            ]
        );
    }
}
