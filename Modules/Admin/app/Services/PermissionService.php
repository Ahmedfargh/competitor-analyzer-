<?php

namespace Modules\Admin\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\PermissionDTOInterface;
use Modules\Admin\Repositories\Contracts\PermissionRepositoryInterface;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Services\Contracts\PermissionServiceInterface;
use Spatie\Permission\Models\Permission;

class PermissionService implements PermissionServiceInterface
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepo,
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * @return Collection<int, Permission>
     */
    public function getAllPermissions(): Collection
    {
        return $this->permissionRepo->all();
    }

    /**
     * @return array<string, array<int, Permission>>
     */
    public function getGroupedPermissions(): array
    {
        $permissions = $this->permissionRepo->all();
        $grouped = [];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name, 2);
            $group = count($parts) > 1 ? $parts[0] : 'general';
            $grouped[$group][] = $permission;
        }

        ksort($grouped);

        return $grouped;
    }

    public function createPermission(PermissionDTOInterface $dto): Permission
    {
        return $this->permissionRepo->create($dto);
    }

    public function deletePermission(int $id): bool
    {
        $permission = $this->permissionRepo->findById($id);
        if (! $permission) {
            return false;
        }

        return $this->permissionRepo->delete($permission);
    }
}
