<?php

namespace Modules\Admin\Services\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\AdminUserDTOInterface;
use Modules\Admin\Models\Admin;

interface AdminUserServiceInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function listUsers(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * @return Collection<int, Admin>
     */
    public function getAllUsers(): Collection;

    public function getUser(int $id): Admin;

    public function createUser(AdminUserDTOInterface $dto): Admin;

    public function updateUser(int $id, AdminUserDTOInterface $dto): Admin;

    public function deleteUser(int $id): bool;
}
