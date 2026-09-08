<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\TenantFeatureCustomization;
use Illuminate\Database\Seeder;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Support\TenantLayerCustomizer;
use Modules\Tenant\Services\CustomTenantActivityLogService;

class TenantFeatureCustomizationSeeder extends Seeder
{
    /**
     * Default customization blueprints: [BaseClass => AlternativeClass].
     *
     * @var array<string, string>
     */
    protected static array $defaultMappings = [
        ActivityLogServiceInterface::class => CustomTenantActivityLogService::class,
    ];

    /**
     * Run the database seeds dynamically.
     *
     * @param  string|null  $tenantId  Optional target tenant ID to seed specifically.
     * @param  array<string, string>  $customMappings  Optional overrides for class mappings.
     */
    public function run(?string $tenantId = null, array $customMappings = []): void
    {
        $mappings = ! empty($customMappings) ? $customMappings : static::getDefaultMappings();

        // 1. If a specific tenant ID is provided, seed for that tenant directly
        if ($tenantId) {
            static::seedForTenant($tenantId, $mappings);

            return;
        }

        // 2. Otherwise, check existing tenants in the system
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            // Provision an enterprise demo tenant for immediate testing
            $demoTenant = Tenant::firstOrCreate(
                ['id' => 'enterprise-demo'],
                ['company_name' => 'Enterprise Demo Tenant']
            );

            $demoTenant->domains()->firstOrCreate(
                ['domain' => 'enterprise.localhost']
            );

            $tenants = collect([$demoTenant]);
        }

        // 3. Dynamically seed customizations for each tenant
        foreach ($tenants as $tenant) {
            static::seedForTenant($tenant->id, $mappings);
        }
    }

    /**
     * Dynamically seed or update customization mappings for a given tenant.
     *
     * @param  string  $tenantId  The target tenant identifier.
     * @param  array<string, string|array{alternative: string, is_active?: bool}>  $mappings
     * @return list<TenantFeatureCustomization>
     */
    public static function seedForTenant(string $tenantId, array $mappings, bool $defaultActive = true): array
    {
        $records = [];

        foreach ($mappings as $baseClass => $definition) {
            $alternativeClass = is_array($definition) ? $definition['alternative'] : $definition;
            $isActive = is_array($definition) ? ($definition['is_active'] ?? $defaultActive) : $defaultActive;

            $record = TenantFeatureCustomization::updateOrCreate(
                [
                    'tenant_id' => $tenantId,
                    'base_class' => $baseClass,
                ],
                [
                    'alternative_class' => $alternativeClass,
                    'is_active' => $isActive,
                ]
            );

            $records[] = $record;
        }

        // Invalidate tenant customization cache so new bindings take effect immediately
        TenantLayerCustomizer::clearTenantCache($tenantId);

        return $records;
    }

    /**
     * Get default class customization mappings.
     *
     * @return array<string, string>
     */
    public static function getDefaultMappings(): array
    {
        return static::$defaultMappings;
    }

    /**
     * Dynamically register or extend default mappings at runtime.
     */
    public static function registerMapping(string $baseClass, string $alternativeClass): void
    {
        static::$defaultMappings[$baseClass] = $alternativeClass;
    }
}
