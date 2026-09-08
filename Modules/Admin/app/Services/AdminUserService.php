<?php

namespace Modules\Admin\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Modules\Admin\DTOs\Contracts\AdminUserDTOInterface;
use Modules\Admin\Models\Admin;
use Modules\Admin\Repositories\Contracts\AdminUserRepositoryInterface;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Services\Contracts\AdminUserServiceInterface;

class AdminUserService implements AdminUserServiceInterface
{
    public function __construct(
        protected AdminUserRepositoryInterface $adminUserRepo,
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * @param  array<string, mixed>  $filters
     */
    public function listUsers(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->adminUserRepo->paginate($filters, $perPage);
    }

    /**
     * @return Collection<int, Admin>
     */
    public function getAllUsers(): Collection
    {
        return $this->adminUserRepo->all();
    }

    public function getUser(int $id): Admin
    {
        $user = $this->adminUserRepo->findById($id);
        if (! $user) {
            throw new ModelNotFoundException("Admin user #{$id} not found.");
        }

        return $user;
    }

    public function createUser(AdminUserDTOInterface $dto): Admin
    {
        return $this->adminUserRepo->create($dto);
    }

    public function updateUser(int $id, AdminUserDTOInterface $dto): Admin
    {
        $admin = $this->getUser($id);

        return $this->adminUserRepo->update($admin, $dto);
    }

    public function deleteUser(int $id): bool
    {
        $admin = $this->getUser($id);

        if (auth('admin')->id() === $admin->id) {
            throw ValidationException::withMessages([
                'user' => __('admin.cannot_delete_self'),
            ]);
        }

        return $this->adminUserRepo->delete($admin);
    }
}
