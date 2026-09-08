<?php

namespace Modules\Admin\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\PermissionDTOInterface;
use Spatie\Permission\Models\Permission;

interface PermissionServiceInterface
{
    /**
     * @return Collection<int, Permission>
     */
    public function getAllPermissions(): Collection;

    /**
     * @return array<string, array<int, Permission>>
     */
    public function getGroupedPermissions(): array;

    public function createPermission(PermissionDTOInterface $dto): Permission;

    public function deletePermission(int $id): bool;
}
