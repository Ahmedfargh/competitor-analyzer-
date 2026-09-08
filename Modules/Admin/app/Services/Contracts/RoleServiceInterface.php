<?php

namespace Modules\Admin\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\RoleDTOInterface;
use Spatie\Permission\Models\Role;

interface RoleServiceInterface
{
    /**
     * @return Collection<int, Role>
     */
    public function getAllRoles(): Collection;

    public function getRole(int $id): Role;

    public function createRole(RoleDTOInterface $dto): Role;

    public function updateRole(int $id, RoleDTOInterface $dto): Role;

    public function deleteRole(int $id): bool;
}
