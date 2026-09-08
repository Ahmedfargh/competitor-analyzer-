<?php

namespace Modules\Admin\Services\Contracts;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Admin\DTOs\Contracts\CreateTenantDTOInterface;
use Modules\Admin\DTOs\Contracts\UpdateTenantDTOInterface;

interface TenantServiceInterface
{
    /**
     * List all tenants with filters and pagination.
     */
    public function listTenants(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find tenant by ID.
     */
    public function getTenant(string $id): Tenant;

    /**
     * Provision a brand-new tenant with domain mapping and database initialization.
     */
    public function provisionTenant(CreateTenantDTOInterface $dto): Tenant;

    /**
     * Update an existing tenant.
     */
    public function updateTenant(string $id, UpdateTenantDTOInterface $dto): Tenant;

    /**
     * Deprovision and delete a tenant.
     */
    public function deprovisionTenant(string $id): bool;

    /**
     * Browse tenant's isolated data (users, competitors, tables) safely from admin context.
     *
     * @return array<string, mixed>
     */
    public function browseTenantData(string $id): array;

    /**
     * Get aggregate tenant statistics for dashboard widgets.
     *
     * @return array<string, mixed>
     */
    public function getTenantOverviewStats(): array;

    /**
     * Create an isolated tenant user directly inside tenant partition.
     *
     * @param  array<string, mixed>  $data
     */
    public function createTenantUser(string $id, array $data): User;

    /**
     * Delete an isolated tenant user directly inside tenant partition.
     */
    public function deleteTenantUser(string $id, int|string $userId): bool;
}
