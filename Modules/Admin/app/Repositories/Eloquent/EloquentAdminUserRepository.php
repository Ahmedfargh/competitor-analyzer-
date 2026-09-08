<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\DTOs\Contracts\AdminUserDTOInterface;
use Modules\Admin\Models\Admin;
use Modules\Admin\Repositories\Contracts\AdminUserRepositoryInterface;

class EloquentAdminUserRepository implements AdminUserRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Admin::with('roles')->latest('id');

        if (! empty($filters['search'])) {
            $search = '%'.trim($filters['search']).'%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search);
            });
        }

        if (! empty($filters['role'])) {
            $role = $filters['role'];
            $query->whereHas('roles', fn ($q) => $q->where('name', $role));
        }

        return $query->paginate($perPage);
    }

    /**
     * @return Collection<int, Admin>
     */
    public function all(): Collection
    {
        return Admin::with('roles')->latest('id')->get();
    }

    public function findById(int $id): ?Admin
    {
        return Admin::with('roles')->find($id);
    }

    public function findByEmail(string $email): ?Admin
    {
        return Admin::where('email', $email)->first();
    }

    public function create(AdminUserDTOInterface $dto): Admin
    {
        $admin = Admin::create([
            'name' => $dto->getName(),
            'email' => $dto->getEmail(),
            'password' => Hash::make($dto->getPassword()),
        ]);

        if (! empty($dto->getRoles())) {
            $admin->syncRoles($dto->getRoles());
        }

        return $admin->load('roles');
    }

    public function update(Admin $admin, AdminUserDTOInterface $dto): Admin
    {
        $payload = [
            'name' => $dto->getName(),
            'email' => $dto->getEmail(),
        ];

        if ($dto->getPassword()) {
            $payload['password'] = Hash::make($dto->getPassword());
        }

        $admin->update($payload);

        if ($dto->getRoles() !== null) {
            $admin->syncRoles($dto->getRoles());
        }

        return $admin->load('roles');
    }

    public function delete(Admin $admin): bool
    {
        return (bool) $admin->delete();
    }
}
