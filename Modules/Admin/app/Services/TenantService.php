<?php

namespace Modules\Admin\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\DTOs\Contracts\CreateTenantDTOInterface;
use Modules\Admin\DTOs\Contracts\UpdateTenantDTOInterface;
use Modules\Admin\Repositories\Contracts\TenantRepositoryInterface;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Services\Contracts\TenantServiceInterface;

class TenantService implements TenantServiceInterface
{
    public function __construct(
        protected TenantRepositoryInterface $tenantRepo,
        protected ActivityLogServiceInterface $activityLogService
    ) {}

    /**
     * List all tenants with filters and pagination.
     */
    public function listTenants(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->tenantRepo->all($filters, $perPage);
    }

    /**
     * Find tenant by ID.
     */
    public function getTenant(string $id): Tenant
    {
        $tenant = $this->tenantRepo->findById($id);
        if (! $tenant) {
            abort(404, "Tenant [{$id}] not found.");
        }

        return $tenant;
    }

    /**
     * Provision a brand-new tenant with domain mapping and database initialization.
     */
    public function provisionTenant(CreateTenantDTOInterface $dto): Tenant
    {
        $tenant = $this->tenantRepo->create($dto);

        return $tenant;
    }

    /**
     * Update an existing tenant.
     */
    public function updateTenant(string $id, UpdateTenantDTOInterface $dto): Tenant
    {
        return $this->tenantRepo->update($id, $dto);
    }

    /**
     * Deprovision and delete a tenant.
     */
    public function deprovisionTenant(string $id): bool
    {
        return $this->tenantRepo->delete($id);
    }

    /**
     * Browse tenant's isolated data (users, tables, counts) safely from admin context.
     *
     * @return array<string, mixed>
     */
    public function browseTenantData(string $id): array
    {
        $tenant = $this->getTenant($id);

        $data = [
            'tenant' => $tenant,
            'domains' => $tenant->domains,
            'database_status' => 'connected',
            'tables' => [],
            'recent_users' => [],
            'counts' => [
                'users' => 0,
            ],
            'error' => null,
        ];

        try {
            $tenant->run(function () use (&$data) {
                // Check if users table exists in tenant DB
                if (DB::getSchemaBuilder()->hasTable('users')) {
                    $data['counts']['users'] = DB::table('users')->count();
                    $data['recent_users'] = DB::table('users')
                        ->select(['id', 'name', 'email', 'created_at'])
                        ->latest('created_at')
                        ->limit(10)
                        ->get()
                        ->toArray();
                }

                // Get exact tables in tenant db
                $rawTables = DB::select('SHOW TABLES');
                $data['tables'] = array_values(array_map(
                    fn ($row) => (string) current((array) $row),
                    $rawTables
                ));
            });
        } catch (\Throwable $e) {
            $data['database_status'] = 'unreachable_or_unmigrated';
            $data['error'] = $e->getMessage();
        } finally {
            if (tenancy()->initialized) {
                tenancy()->end();
            }
        }

        return $data;
    }

    /**
     * Get aggregate tenant statistics for dashboard widgets.
     *
     * @return array<string, mixed>
     */
    public function getTenantOverviewStats(): array
    {
        return $this->tenantRepo->getStats();
    }

    /**
     * Create an isolated tenant user directly inside tenant partition.
     *
     * @param  array<string, mixed>  $data
     */
    public function createTenantUser(string $id, array $data): User
    {
        $tenant = $this->getTenant($id);

        try {
            return $tenant->run(function () use ($data) {
                return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'email_verified_at' => now(),
                ]);
            });
        } finally {
            if (tenancy()->initialized) {
                tenancy()->end();
            }
        }
    }

    /**
     * Delete an isolated tenant user directly inside tenant partition.
     */
    public function deleteTenantUser(string $id, int|string $userId): bool
    {
        $tenant = $this->getTenant($id);

        try {
            return $tenant->run(function () use ($userId) {
                $user = User::find($userId);

                return $user ? (bool) $user->delete() : false;
            });
        } finally {
            if (tenancy()->initialized) {
                tenancy()->end();
            }
        }
    }
}
