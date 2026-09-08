<?php

namespace Modules\Admin\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\PermissionDTOInterface;
use Spatie\Permission\Models\Permission;

interface PermissionRepositoryInterface
{
    /**
     * @return Collection<int, Permission>
     */
    public function all(): Collection;

    public function findById(int $id): ?Permission;

    public function findByName(string $name, string $guardName = 'admin'): ?Permission;

    public function create(PermissionDTOInterface $dto): Permission;

    public function delete(Permission $permission): bool;
}
