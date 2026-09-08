<?php

namespace Modules\Admin\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Admin\DTOs\Contracts\AdminUserDTOInterface;
use Modules\Admin\Models\Admin;

interface AdminUserRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * @return Collection<int, Admin>
     */
    public function all(): Collection;

    public function findById(int $id): ?Admin;

    public function findByEmail(string $email): ?Admin;

    public function create(AdminUserDTOInterface $dto): Admin;

    public function update(Admin $admin, AdminUserDTOInterface $dto): Admin;

    public function delete(Admin $admin): bool;
}
