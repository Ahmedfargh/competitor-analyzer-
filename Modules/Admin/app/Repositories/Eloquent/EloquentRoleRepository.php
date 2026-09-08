<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\RoleDTOInterface;
use Modules\Admin\Repositories\Contracts\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    /**
     * @return Collection<int, Role>
     */
    public function all(): Collection
    {
        return Role::where('guard_name', 'admin')
            ->with('permissions')
            ->withCount('users')
            ->latest('id')
            ->get();
    }

    public function findById(int $id): ?Role
    {
        return Role::where('guard_name', 'admin')
            ->with('permissions')
            ->withCount('users')
            ->find($id);
    }

    public function findByName(string $name, string $guardName = 'admin'): ?Role
    {
        return Role::where('guard_name', $guardName)
            ->where('name', $name)
            ->with('permissions')
            ->withCount('users')
            ->first();
    }

    public function create(RoleDTOInterface $dto): Role
    {
        $role = Role::create([
            'name' => $dto->getName(),
            'guard_name' => $dto->getGuardName(),
        ]);

        if (! empty($dto->getPermissions())) {
            $role->syncPermissions($dto->getPermissions());
        }

        return $role->load('permissions');
    }

    public function update(Role $role, RoleDTOInterface $dto): Role
    {
        $role->update([
            'name' => $dto->getName(),
        ]);

        $role->syncPermissions($dto->getPermissions());

        return $role->load('permissions');
    }

    public function delete(Role $role): bool
    {
        return (bool) $role->delete();
    }
}
