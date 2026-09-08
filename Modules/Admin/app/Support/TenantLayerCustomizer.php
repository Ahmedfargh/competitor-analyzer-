<?php

namespace Modules\Admin\Support;

use App\Models\Tenant;
use App\Models\TenantFeatureCustomization;
use Illuminate\Support\Facades\Cache;

class TenantLayerCustomizer
{
    /**
     * Map of tenant-specific custom layer overrides.
     * Format: [tenant_id => [InterfaceClass => ConcreteClass]]
     *
     * @var array<string, array<string, string|callable>>
     */
    protected static array $tenantOverrides = [];

    /**
     * Cache duration for tenant feature customizations in seconds (default: 24 hours).
     */
    public static int $cacheTtl = 86400;

    /**
     * Register a customized layer (DTO, FormRequest, Service, Repository) for a specific tenant.
     */
    public static function register(string $tenantId, string $interface, string|callable $implementation): void
    {
        static::$tenantOverrides[$tenantId][$interface] = $implementation;
    }

    /**
     * Determine current tenant identifier from parameter, tenancy context, or HTTP route.
     */
    public static function resolveCurrentTenantId(?string $tenantId = null): ?string
    {
        if ($tenantId !== null && $tenantId !== '') {
            return $tenantId;
        }

        if (function_exists('tenant') && tenant()) {
            $t = tenant();

            return is_object($t) ? ($t->id ?? (string) $t) : (string) $t;
        }

        if (function_exists('tenancy') && tenancy()->initialized && tenancy()->tenant) {
            return (string) tenancy()->tenant->getTenantKey();
        }

        $routeTenant = request()?->route('tenant') ?? request()?->route('id');
        if ($routeTenant instanceof Tenant) {
            return (string) $routeTenant->id;
        }

        if (is_string($routeTenant) && $routeTenant !== '') {
            return $routeTenant;
        }

        return null;
    }

    /**
     * Get cached active feature customizations map for a tenant.
     * Format: [base_class => alternative_class]
     *
     * @return array<string, string>
     */
    public static function getCustomizationsForTenant(string $tenantId): array
    {
        try {
            return Cache::remember(
                "tenant_customizations:{$tenantId}",
                static::$cacheTtl,
                function () use ($tenantId) {
                    return TenantFeatureCustomization::query()
                        ->active()
                        ->where('tenant_id', $tenantId)
                        ->pluck('alternative_class', 'base_class')
                        ->toArray();
                }
            );
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Clear cached customizations for a specific tenant.
     */
    public static function clearTenantCache(string $tenantId): void
    {
        try {
            Cache::forget("tenant_customizations:{$tenantId}");
        } catch (\Throwable) {
            // Ignore cache store issues if any
        }

        unset(static::$tenantOverrides[$tenantId]);
    }

    /**
     * Resolve the target class name for a base class based on tenant customization.
     */
    public static function resolveClass(string $baseClass, ?string $defaultImplementation = null, ?string $tenantId = null): string
    {
        $currentTenantId = static::resolveCurrentTenantId($tenantId);

        if ($currentTenantId) {
            // 1. Check in-memory overrides first
            if (isset(static::$tenantOverrides[$currentTenantId][$baseClass])) {
                $override = static::$tenantOverrides[$currentTenantId][$baseClass];
                if (is_string($override)) {
                    return $override;
                }
            }

            // 2. Check cached database customizations map for this tenant
            $customizations = static::getCustomizationsForTenant($currentTenantId);

            if (isset($customizations[$baseClass]) && ! empty($customizations[$baseClass])) {
                $alternative = $customizations[$baseClass];
                if (class_exists($alternative)) {
                    return $alternative;
                }
            }
        }

        return $defaultImplementation ?? $baseClass;
    }

    /**
     * Resolve the implementation for an interface/base class based on current tenant context or default.
     * Looks for base class and its alternative in database; if found, loads alternative using namespace.
     */
    public static function resolve(string $interface, ?string $defaultImplementation = null, ?string $tenantId = null): mixed
    {
        $currentTenantId = static::resolveCurrentTenantId($tenantId);

        if ($currentTenantId && isset(static::$tenantOverrides[$currentTenantId][$interface])) {
            $custom = static::$tenantOverrides[$currentTenantId][$interface];
            if (is_callable($custom)) {
                return app()->call($custom);
            }

            if (is_string($custom) && class_exists($custom)) {
                return app()->make($custom);
            }
        }

        $resolvedClass = static::resolveClass($interface, $defaultImplementation, $currentTenantId);

        return app()->make($resolvedClass);
    }

    /**
     * Clear registered overrides (useful in testing).
     */
    public static function flush(): void
    {
        static::$tenantOverrides = [];
    }
}
