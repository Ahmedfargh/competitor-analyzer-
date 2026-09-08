<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\PermissionDTOInterface;
use Modules\Admin\Repositories\Contracts\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    /**
     * @return Collection<int, Permission>
     */
    public function all(): Collection
    {
        return Permission::where('guard_name', 'admin')
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id): ?Permission
    {
        return Permission::where('guard_name', 'admin')->find($id);
    }

    public function findByName(string $name, string $guardName = 'admin'): ?Permission
    {
        return Permission::where('guard_name', $guardName)
            ->where('name', $name)
            ->first();
    }

    public function create(PermissionDTOInterface $dto): Permission
    {
        return Permission::create([
            'name' => $dto->getName(),
            'guard_name' => $dto->getGuardName(),
        ]);
    }

    public function delete(Permission $permission): bool
    {
        return (bool) $permission->delete();
    }
}
