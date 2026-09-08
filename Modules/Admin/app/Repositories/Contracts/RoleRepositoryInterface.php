<?php

namespace Modules\Admin\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\RoleDTOInterface;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface
{
    /**
     * @return Collection<int, Role>
     */
    public function all(): Collection;

    public function findById(int $id): ?Role;

    public function findByName(string $name, string $guardName = 'admin'): ?Role;

    public function create(RoleDTOInterface $dto): Role;

    public function update(Role $role, RoleDTOInterface $dto): Role;

    public function delete(Role $role): bool;
}
