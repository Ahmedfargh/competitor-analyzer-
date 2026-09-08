<?php

namespace Modules\Admin\Repositories\Eloquent;

use App\Models\Tenant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Admin\DTOs\Contracts\CreateTenantDTOInterface;
use Modules\Admin\DTOs\Contracts\UpdateTenantDTOInterface;
use Modules\Admin\Repositories\Contracts\TenantRepositoryInterface;

class EloquentTenantRepository implements TenantRepositoryInterface
{
    /**
     * Get paginated tenants with optional search and filters.
     */
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Tenant::query()->with(['domains']);

        if (! empty($filters['search'])) {
            $search = '%'.trim($filters['search']).'%';
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', $search)
                    ->orWhere('data->company_name', 'like', $search)
                    ->orWhereHas('domains', function ($sub) use ($search) {
                        $sub->where('domain', 'like', $search);
                    });
            });
        }

        if (! empty($filters['plan_id'])) {
            $query->where('data->plan_id', $filters['plan_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find a tenant by primary identifier.
     */
    public function findById(string $id): ?Tenant
    {
        return Tenant::with(['domains'])->find($id);
    }

    /**
     * Create and provision a tenant.
     */
    public function create(CreateTenantDTOInterface $dto): Tenant
    {
        $tenant = Tenant::create([
            'id' => $dto->getTenantId(),
            'company_name' => $dto->getCompanyName(),
            'plan_id' => $dto->getPlanId(),
            'metadata' => $dto->getMetadata(),
        ]);

        try {
            $tenant->domains()->create([
                'domain' => $dto->getDomain(),
            ]);
        } catch (\Throwable $e) {
            $tenant->delete();

            throw $e;
        }

        return $tenant->load('domains');
    }

    /**
     * Update tenant metadata or configuration.
     */
    public function update(string $id, UpdateTenantDTOInterface $dto): Tenant
    {
        return DB::transaction(function () use ($id, $dto) {
            $tenant = Tenant::with('domains')->findOrFail($id);

            if ($dto->getCompanyName() !== null) {
                $tenant->company_name = $dto->getCompanyName();
            }

            if ($dto->getPlanId() !== null) {
                $tenant->plan_id = $dto->getPlanId();
            }

            if ($dto->getMetadata() !== null) {
                $tenant->metadata = array_merge($tenant->metadata ?? [], $dto->getMetadata());
            }

            if ($dto->getProductProfile() !== null) {
                $tenant->updateProductProfile($dto->getProductProfile());
            } else {
                $tenant->save();
            }

            if ($dto->getDomain() !== null) {
                $existingDomain = $tenant->domains->first();
                if ($existingDomain) {
                    $existingDomain->update(['domain' => $dto->getDomain()]);
                } else {
                    $tenant->domains()->create(['domain' => $dto->getDomain()]);
                }
            }

            return $tenant->fresh(['domains']);
        });
    }

    /**
     * Delete a tenant and its domains/database.
     */
    public function delete(string $id): bool
    {
        $tenant = Tenant::find($id);
        if (! $tenant) {
            return false;
        }

        return (bool) $tenant->delete();
    }

    /**
     * Get tenant summary statistics (total, active, recent).
     */
    public function getStats(): array
    {
        $total = Tenant::count();
        $recentCount = Tenant::where('created_at', '>=', now()->subDays(30))->count();

        return [
            'total_tenants' => $total,
            'recent_tenants_30d' => $recentCount,
        ];
    }
}
