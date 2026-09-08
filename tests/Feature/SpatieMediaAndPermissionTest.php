<?php

namespace Tests\Feature;

use App\Listeners\ClearPermissionCache;
use App\Models\User;
use App\Providers\TenancyServiceProvider;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Admin\Models\Admin;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Events\RevertedToCentralContext;
use Stancl\Tenancy\Events\TenancyBootstrapped;
use Tests\TestCase;

class SpatieMediaAndPermissionTest extends TestCase
{
    /**
     * Test central migrations include Spatie Media and Permission migrations.
     */
    public function test_central_migrations_include_spatie_files(): void
    {
        $centralFiles = glob(database_path('migrations/*create_permission_tables*.php'));
        $this->assertNotEmpty($centralFiles, 'Central migrations must include create_permission_tables migration.');

        $mediaFiles = glob(database_path('migrations/*create_media_table*.php'));
        $this->assertNotEmpty($mediaFiles, 'Central migrations must include create_media_table migration.');
    }

    /**
     * Test tenant migrations are located in their respective modules and discovered by tenancy configuration.
     */
    public function test_tenant_migrations_include_spatie_and_tenant_files(): void
    {
        $tenantPermissionFiles = glob(base_path('Modules/Tenant/database/migrations/tenant/*create_permission_tables*.php'));
        $this->assertNotEmpty($tenantPermissionFiles, 'Tenant module must include create_permission_tables migration.');

        $tenantMediaFiles = glob(base_path('Modules/Tenant/database/migrations/tenant/*create_media_table*.php'));
        $this->assertNotEmpty($tenantMediaFiles, 'Tenant module must include create_media_table migration.');

        $tenantUserFiles = glob(base_path('Modules/User/database/migrations/tenant/*create_tenant_users_table*.php'));
        $this->assertNotEmpty($tenantUserFiles, 'User module must include create_tenant_users_table migration.');

        // Verify Stancl Tenancy discovery via migration_parameters
        $resolvedMigrations = app('migrator')->getMigrationFiles(config('tenancy.migration_parameters.--path'));
        $this->assertArrayHasKey('2026_09_08_000001_create_tenant_users_table', $resolvedMigrations);
        $this->assertArrayHasKey('2026_09_08_141809_create_permission_tables', $resolvedMigrations);
        $this->assertArrayHasKey('2026_09_08_141809_create_media_table', $resolvedMigrations);
    }

    /**
     * Test Admin model implements Spatie HasMedia and uses HasRoles with admin guard.
     */
    public function test_admin_model_has_spatie_traits_and_guard(): void
    {
        $admin = new Admin;

        $this->assertInstanceOf(HasMedia::class, $admin);
        $this->assertContains(HasRoles::class, class_uses_recursive(Admin::class));
        $this->assertContains(InteractsWithMedia::class, class_uses_recursive(Admin::class));

        // Check guard_name attribute
        $reflector = new \ReflectionClass(Admin::class);
        $property = $reflector->getProperty('guard_name');
        $property->setAccessible(true);
        $this->assertEquals('admin', $property->getValue($admin));

        // Check media relation
        $this->assertInstanceOf(MorphMany::class, $admin->media());
    }

    /**
     * Test User model implements Spatie HasMedia and uses HasRoles with tenant guard.
     */
    public function test_user_model_has_spatie_traits_and_guard(): void
    {
        $user = new User;

        $this->assertInstanceOf(HasMedia::class, $user);
        $this->assertContains(HasRoles::class, class_uses_recursive(User::class));
        $this->assertContains(InteractsWithMedia::class, class_uses_recursive(User::class));

        // Check guard_name attribute
        $reflector = new \ReflectionClass(User::class);
        $property = $reflector->getProperty('guard_name');
        $property->setAccessible(true);
        $this->assertEquals('tenant', $property->getValue($user));

        // Check media relation
        $this->assertInstanceOf(MorphMany::class, $user->media());
    }

    /**
     * Test ClearPermissionCache listener is registered in TenancyServiceProvider.
     */
    public function test_clear_permission_cache_listener_registered(): void
    {
        $provider = new TenancyServiceProvider($this->app);
        $events = $provider->events();

        $this->assertArrayHasKey(TenancyBootstrapped::class, $events);
        $this->assertContains(ClearPermissionCache::class, $events[TenancyBootstrapped::class]);

        $this->assertArrayHasKey(RevertedToCentralContext::class, $events);
        $this->assertContains(ClearPermissionCache::class, $events[RevertedToCentralContext::class]);

        // Verify handle executes without exception
        $listener = new ClearPermissionCache;
        $listener->handle();
        $this->assertTrue(true);
    }
}
