<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Modules\Admin\Models\Admin;
use Tests\TestCase;

class AuthGuardsTest extends TestCase
{
    /**
     * Test admin guard is configured with the admin model and provider.
     */
    public function test_admin_guard_is_configured_with_correct_provider_and_model(): void
    {
        $this->assertEquals('session', config('auth.guards.admin.driver'));
        $this->assertEquals('admins', config('auth.guards.admin.provider'));
        $this->assertEquals(Admin::class, config('auth.providers.admins.model'));

        $provider = Auth::guard('admin')->getProvider();
        $this->assertEquals(Admin::class, $provider->getModel());
    }

    /**
     * Test tenant guard is configured with the user model and provider.
     */
    public function test_tenant_guard_is_configured_with_correct_provider_and_model(): void
    {
        $this->assertEquals('session', config('auth.guards.tenant.driver'));
        $this->assertEquals('tenant_users', config('auth.guards.tenant.provider'));
        $this->assertEquals(User::class, config('auth.providers.tenant_users.model'));

        $provider = Auth::guard('tenant')->getProvider();
        $this->assertEquals(User::class, $provider->getModel());
    }

    /**
     * Test password brokers are properly configured for both guards.
     */
    public function test_password_brokers_are_configured_for_both_guards(): void
    {
        $adminBroker = Password::broker('admins');
        $this->assertNotNull($adminBroker);

        $tenantBroker = Password::broker('tenant_users');
        $this->assertNotNull($tenantBroker);
    }

    /**
     * Test that admin and tenant authentication guards are strictly isolated in memory.
     */
    public function test_admin_and_tenant_guards_are_isolated(): void
    {
        $admin = new Admin([
            'name' => 'System Admin',
            'email' => 'admin@system.test',
        ]);
        $admin->id = 1;

        $user = new User([
            'name' => 'Tenant User',
            'email' => 'user@tenant.test',
        ]);
        $user->id = 1;

        // Log in to admin guard
        Auth::guard('admin')->setUser($admin);

        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertFalse(Auth::guard('tenant')->check());
        $this->assertInstanceOf(Admin::class, Auth::guard('admin')->user());

        // Log out admin, log in to tenant guard
        Auth::guard('admin')->logout();
        Auth::guard('tenant')->setUser($user);

        $this->assertFalse(Auth::guard('admin')->check());
        $this->assertTrue(Auth::guard('tenant')->check());
        $this->assertInstanceOf(User::class, Auth::guard('tenant')->user());
    }

    /**
     * Test unauthenticated access to admin routes is rejected.
     */
    public function test_unauthenticated_admin_route_rejects(): void
    {
        $response = $this->getJson('/admins');

        $response->assertStatus(401);
    }

    /**
     * Test admin authenticated user can access admin protected routes.
     */
    public function test_admin_authenticated_user_can_access_admin_route(): void
    {
        $admin = new Admin([
            'name' => 'System Admin',
            'email' => 'admin@system.test',
        ]);
        $admin->id = 1;

        $response = $this->actingAs($admin, 'admin')->get('/admins');

        $response->assertOk();
    }

    /**
     * Test tenant authenticated user cannot access admin protected routes.
     */
    public function test_tenant_authenticated_user_cannot_access_admin_route(): void
    {
        $user = new User([
            'name' => 'Tenant User',
            'email' => 'user@tenant.test',
        ]);
        $user->id = 1;

        $response = $this->actingAs($user, 'tenant')->getJson('/admins');

        $response->assertStatus(401);
    }
}
