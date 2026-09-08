---
name: stancl-tenancy-development
description: "Stancl Tenancy v3 multi-tenancy development in Laravel. Use when building multi-tenant features, configuring tenant isolation (database-per-tenant), handling tenant routing (central vs tenant domains, identification middleware), writing tenant migrations/seeders, switching tenant context programmatically, running tenant-aware queues/jobs, preventing cross-tenant data leaks, or testing multi-tenant applications."
license: MIT
metadata:
  author: laravel
---

# Stancl Tenancy Development

This application uses `stancl/tenancy` v3 for multi-tenancy with database-per-tenant isolation.

## Architecture & Foundational Concepts

- **Tenant Identification**: Tenants are identified primarily by domain or subdomain using `InitializeTenancyByDomain` (or `InitializeTenancyBySubdomain`).
- **Separation of Concerns**:
  - Central routes live in `routes/web.php` and `routes/api.php`, guarded by `PreventAccessFromCentralDomains` or dedicated central domain route groups.
  - Tenant routes live in `routes/tenant.php` and are wrapped with `web` + `InitializeTenancyByDomain` + `PreventAccessFromCentralDomains`.
- **Database Separation**:
  - Central migrations live in `database/migrations`.
  - Tenant-specific migrations live in `database/migrations/tenant`.
  - Never place tenant table migrations in the central migrations folder.

---

## Route Configuration

Tenant routes in `routes/tenant.php` must follow this structure:

```php
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'Tenant: ' . tenant('id');
    })->name('tenant.dashboard');
});
```

Central routes must not run tenant initialization middleware. Ensure central domains are properly configured in `config/tenancy.php` under `'central_domains'`.

---

## Tenant Context Management

### Programmatic Context Switching

When executing code on behalf of a tenant in console commands, central controllers, or background listeners, always initialize and end tenancy cleanly:

```php
// Initialize tenant context
tenancy()->initialize($tenant);

try {
    // Run queries / actions inside tenant database
    $count = User::count();
} finally {
    // Always end tenancy to revert connection and global scopes
    tenancy()->end();
}
```

Or use the safe closure helper:

```php
$tenant->run(function () {
    return User::where('active', true)->get();
});
```

### Accessing Tenant Data
- Get current tenant instance: `tenant()`
- Get tenant attribute: `tenant('id')` or `tenant()->name`
- Check if tenancy is active: `tenancy()->initialized`

---

## Migrations & Database Operations

- Run tenant migrations across all tenants:
  ```bash
  php artisan tenants:migrate
  ```
- Run tenant seeders:
  ```bash
  php artisan tenants:seed
  ```
- Execute artisan command for specific tenant(s):
  ```bash
  php artisan tenants:run --tenants=tenant_uuid "route:list"
  ```
- Creating tenant migrations: Place files in `database/migrations/tenant/`.
  ```bash
  php artisan make:migration create_orders_table --path=database/migrations/tenant
  ```

---

## Queues & Background Jobs

Background jobs dispatched inside tenant context must be tenant-aware so that when the worker picks up the job, it connects to the correct tenant database:

```php
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stancl\Tenancy\Queue\TenantAwareJob;

class ProcessTenantOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use TenantAwareJob; // Automatically restores tenant database context when job is executed

    public function handle(): void
    {
        // Executes within the tenant's database connection
    }
}
```

---

## Cross-Tenant Data Leakage Prevention

1. **Static / Singleton State**: Avoid storing tenant-specific data in singletons or static variables. In long-running processes (Octane, queue workers), state can persist across tenant boundaries.
2. **File Storage Isolation**: Use tenant disk or tenant-scoped root path for uploads (`tenancy()->tenant->run(...)` or configured tenant storage driver). Never save tenant files to a flat central storage folder without prefixing/isolating.
3. **Cache Partitioning**: Ensure tenant cache uses a tenant-specific cache prefix or tag to prevent key collisions across tenants.
4. **Broadcasting & Real-Time**: Private and presence broadcast channels for tenant users must be scoped by tenant ID (`tenant.{tenant_id}.orders`).

---

## Testing Multi-Tenant Applications

In tests, always set up a tenant and initialize tenancy before hitting tenant routes:

```php
public function test_tenant_user_can_access_dashboard(): void
{
    $tenant = Tenant::create(['id' => 'test-company']);
    $tenant->domains()->create(['domain' => 'test-company.localhost']);

    $response = $this->get('http://test-company.localhost/dashboard');

    $response->assertOk();
}
```

For database testing inside tenant context:
```php
$tenant->run(function () {
    $this->assertDatabaseHas('users', ['email' => 'admin@test.com']);
});
```
