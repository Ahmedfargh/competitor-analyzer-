# 🧩 Tenant Customization Layer & Dynamic Class Override Architecture

The **Tenant Customization Layer** in Compitator AI allows enterprise tenants to override default application behaviors (Services, Repositories, Form Requests, DTOs, Scrapers) with custom implementations, without modifying shared core code or branching the monolith.

---

## 🏗️ Architecture Overview

The system uses a 3-tier resolution strategy orchestrated by [`TenantLayerCustomizer`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Support/TenantLayerCustomizer.php):

```text
                                  Service Resolution Request
                                               │
                                               ▼
                             ┌───────────────────────────────────┐
                             │    TenantLayerCustomizer::resolve  │
                             └─────────────────┬─────────────────┘
                                               │
                                               ▼
                                 [1] In-Memory Code Override?
                                  (TenantLayerCustomizer::register)
                                         /           \
                                       YES            NO
                                       /               \
                       Return Custom Instance           ▼
                                            [2] Database Customization?
                                             (tenant_feature_customizations)
                                             (Cached via Cache::remember)
                                                    /         \
                                                  YES          NO
                                                  /             \
                                  Return Alternative Class       ▼
                                                   [3] Default Implementation
                                                   (Core Framework Class)
```

---

## 🔍 How Resolution Works

### 1. Automatic Tenant Context Detection
[`TenantLayerCustomizer::resolveCurrentTenantId()`](file:///home/ahmed/laravel-tech/compitator-project/Modules/Admin/app/Support/TenantLayerCustomizer.php) automatically discovers the active tenant context using:
1. Explicit `$tenantId` argument (if passed directly).
2. Active Stancl Tenancy context via `tenant('id')` or `tenancy()->tenant`.
3. Current HTTP route parameter (e.g. `{tenant}` or `{id}`).

### 2. Resolution Methods

#### `resolve(string $interface, ?string $defaultImplementation = null, ?string $tenantId = null)`
Resolves the service or repository instance from the Laravel Service Container.
```php
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Services\ActivityLogService;
use Modules\Admin\Support\TenantLayerCustomizer;

$activityService = TenantLayerCustomizer::resolve(
    ActivityLogServiceInterface::class, 
    ActivityLogService::class
);
```

#### `resolveClass(string $baseClass, ?string $defaultImplementation = null, ?string $tenantId = null)`
Resolves the target FQCN string (ideal for Form Requests and DTOs).
```php
$requestClass = TenantLayerCustomizer::resolveClass(
    StoreTenantRequestInterface::class,
    StoreTenantRequest::class
);
```

---

## 🗄️ Database Schema (`tenant_feature_customizations`)

Customizations can be enabled, disabled, or configured per tenant via the central database:

| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key |
| `tenant_id` | `VARCHAR(255)` | Foreign Key referencing `tenants.id` |
| `feature_key` | `VARCHAR(100)` | Unique feature descriptor (e.g. `audit_log`, `pricing_engine`) |
| `base_class` | `VARCHAR(255)` | Interface or base class to be replaced |
| `alternative_class` | `VARCHAR(255)` | Tenant-specific custom class to load |
| `is_active` | `BOOLEAN` | Feature toggle (`true`/`false`) |
| `metadata` | `JSON` | Optional tenant-specific parameters |

### Eloquent Model: [`TenantFeatureCustomization.php`](file:///home/ahmed/laravel-tech/compitator-project/app/Models/TenantFeatureCustomization.php)
```php
use App\Models\TenantFeatureCustomization;

TenantFeatureCustomization::create([
    'tenant_id' => 'acme',
    'feature_key' => 'audit_logging',
    'base_class' => \Modules\Admin\Services\Contracts\ActivityLogServiceInterface::class,
    'alternative_class' => \Modules\Tenant\Services\CustomTenantActivityLogService::class,
    'is_active' => true,
    'metadata' => [
        'webhook_url' => 'https://acme.internal/audit',
        'encrypt_payloads' => true,
    ],
]);
```

---

## ⚡ High-Performance Caching & Automatic Invalidation

Database customizations are cached using Laravel's Cache subsystem to eliminate repeated database queries on every request:

- **Cache Key**: `tenant_customizations:{tenant_id}`
- **Cache TTL**: 24 Hours (86,400 seconds)
- **Automatic Invalidation**:
  [`TenantFeatureCustomizationObserver`](file:///home/ahmed/laravel-tech/compitator-project/app/Observers/TenantFeatureCustomizationObserver.php) observes the model and automatically flushes the cache upon:
  - `created`: Invalidate cache & write central activity log.
  - `updated`: Invalidate cache & log parameter diff.
  - `deleted`: Invalidate cache & record deprovision event.

Manual Cache Invalidation:
```php
TenantLayerCustomizer::clearTenantCache('acme');
```

---

## 🛠️ Implementation Recipes

### Recipe 1: Service Provider Integration
Bind interfaces dynamically in your module's `ServiceProvider`:
```php
// In Modules/Admin/app/Providers/AdminServiceProvider.php

$this->app->bind(ActivityLogServiceInterface::class, function () {
    return TenantLayerCustomizer::resolve(
        ActivityLogServiceInterface::class,
        ActivityLogService::class
    );
});
```

### Recipe 2: Runtime Code Overrides
Useful for integration tests or programmatic overrides in tenant-aware middleware:
```php
use Modules\Admin\Support\TenantLayerCustomizer;

TenantLayerCustomizer::register('enterprise-client', ActivityLogServiceInterface::class, function ($app) {
    return new EnterpriseSyslogService(config('services.syslog.host'));
});
```

### Recipe 3: Dynamic Seeding via [`TenantFeatureCustomizationSeeder`](file:///home/ahmed/laravel-tech/compitator-project/database/seeders/TenantFeatureCustomizationSeeder.php)
Run the built-in customization seeder:
```bash
php artisan db:seed --class=TenantFeatureCustomizationSeeder
```

---

## 🧪 Testing Tenant Customizations

When writing unit or feature tests, always clean in-memory registrations in `tearDown()`:

```php
use Tests\TestCase;
use Modules\Admin\Support\TenantLayerCustomizer;

class CustomizationTest extends TestCase
{
    protected function tearDown(): void
    {
        TenantLayerCustomizer::flush();
        parent::tearDown();
    }

    public function test_tenant_uses_custom_service(): void
    {
        TenantLayerCustomizer::register('test-tenant', MyServiceInterface::class, MyCustomService::class);

        $service = TenantLayerCustomizer::resolve(MyServiceInterface::class, MyDefaultService::class, 'test-tenant');

        $this->assertInstanceOf(MyCustomService::class, $service);
    }
}
```

Run test suite:
```bash
php artisan test --compact --filter=TenantFeatureCustomizationTest
```
