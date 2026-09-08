<?php

namespace Modules\Admin\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Modules\Admin\DTOs\Contracts\RoleDTOInterface;
use Modules\Admin\Repositories\Contracts\RoleRepositoryInterface;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Services\Contracts\RoleServiceInterface;
use Spatie\Permission\Models\Role;

class RoleService implements RoleServiceInterface
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepo,
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * @return Collection<int, Role>
     */
    public function getAllRoles(): Collection
    {
        return $this->roleRepo->all();
    }

    public function getRole(int $id): Role
    {
        $role = $this->roleRepo->findById($id);
        if (! $role) {
            throw new ModelNotFoundException("Role #{$id} not found.");
        }

        return $role;
    }

    public function createRole(RoleDTOInterface $dto): Role
    {
        return $this->roleRepo->create($dto);
    }

    public function updateRole(int $id, RoleDTOInterface $dto): Role
    {
        $role = $this->getRole($id);

        return $this->roleRepo->update($role, $dto);
    }

    public function deleteRole(int $id): bool
    {
        $role = $this->getRole($id);

        if ($role->name === 'super_admin') {
            throw ValidationException::withMessages([
                'role' => __('admin.cannot_delete_super_admin'),
            ]);
        }

        return $this->roleRepo->delete($role);
    }
}
