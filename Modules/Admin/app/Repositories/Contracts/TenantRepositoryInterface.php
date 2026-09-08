<?php

namespace Modules\Admin\Repositories\Contracts;

use App\Models\Tenant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Admin\DTOs\Contracts\CreateTenantDTOInterface;
use Modules\Admin\DTOs\Contracts\UpdateTenantDTOInterface;

interface TenantRepositoryInterface
{
    /**
     * Get paginated tenants with optional search and filters.
     */
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a tenant by primary identifier.
     */
    public function findById(string $id): ?Tenant;

    /**
     * Create and provision a tenant.
     */
    public function create(CreateTenantDTOInterface $dto): Tenant;

    /**
     * Update tenant metadata or configuration.
     */
    public function update(string $id, UpdateTenantDTOInterface $dto): Tenant;

    /**
     * Delete a tenant and its domains/database.
     */
    public function delete(string $id): bool;

    /**
     * Get tenant summary statistics (total, active, recent).
     */
    public function getStats(): array;
}
