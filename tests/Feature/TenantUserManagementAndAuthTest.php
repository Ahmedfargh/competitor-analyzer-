<?php

namespace Tests\Feature;

use App\Livewire\Admin\Tenants\TenantDataBrowser;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\Admin\Models\Admin;
use Modules\Admin\Services\Contracts\TenantServiceInterface;
use Tests\TestCase;

class TenantUserManagementAndAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected Admin $admin;

    protected Tenant $tenant;

    protected string $tenantDomain;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::firstOrCreate(
            ['email' => 'tenant-user-admin@system.test'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $tenantId = 'tu-test-'.uniqid();
        $this->tenant = Tenant::create([
            'id' => $tenantId,
            'company_name' => 'Apex Technologies',
        ]);

        $this->tenantDomain = $tenantId.'.localhost';
        $this->tenant->domains()->create([
            'domain' => $this->tenantDomain,
        ]);
    }

    protected function tearDown(): void
    {
        if (tenancy()->initialized) {
            tenancy()->end();
        }

        parent::tearDown();
    }

    /**
     * Test tenant database seeder automatically provisions initial admin user in tenant partition.
     */
    public function test_tenant_seeding_creates_initial_tenant_admin_user(): void
    {
        $this->tenant->run(function () {
            $user = User::where('email', "admin@{$this->tenant->id}.localhost")->first();

            $this->assertNotNull($user);
            $this->assertEquals('Apex Technologies', $user->name);
            $this->assertTrue(Hash::check('password', $user->password));
        });

        // Ensure user does NOT exist in central database
        $this->assertFalse(User::where('email', "admin@{$this->tenant->id}.localhost")->exists());
    }

    /**
     * Test UserObserver records tenant user events into central activity log.
     */
    public function test_user_observer_logs_activity_to_central_database(): void
    {
        $uniqueEmail = 'observer-'.uniqid().'@apex.test';

        $userId = null;
        $this->tenant->run(function () use ($uniqueEmail, &$userId) {
            $user = User::create([
                'name' => 'Sarah Connor',
                'email' => $uniqueEmail,
                'password' => Hash::make('Secret123!'),
            ]);
            $userId = $user->id;

            $user->update(['name' => 'Sarah Connor Updated']);
            $user->delete();
        });

        // Central activity logs should contain create, update, and delete entries
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tenant_user.created',
            'subject_type' => User::class,
            'subject_id' => (string) $userId,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tenant_user.updated',
            'subject_type' => User::class,
            'subject_id' => (string) $userId,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tenant_user.deleted',
            'subject_type' => User::class,
            'subject_id' => (string) $userId,
        ]);
    }

    /**
     * Test TenantService can create and delete tenant users inside tenant partition.
     */
    public function test_tenant_service_can_create_and_delete_tenant_users(): void
    {
        $service = app(TenantServiceInterface::class);
        $email = 'service-user-'.uniqid().'@apex.test';

        $createdUser = $service->createTenantUser($this->tenant->id, [
            'name' => 'Alex Mercer',
            'email' => $email,
            'password' => 'Password123!',
        ]);

        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertEquals($email, $createdUser->email);

        // Verify inside tenant partition
        $this->tenant->run(function () use ($email) {
            $this->assertTrue(User::where('email', $email)->exists());
        });

        // Delete user via service
        $deleted = $service->deleteTenantUser($this->tenant->id, $createdUser->id);
        $this->assertTrue($deleted);

        // Verify removed from tenant partition
        $this->tenant->run(function () use ($email) {
            $this->assertFalse(User::where('email', $email)->exists());
        });
    }

    /**
     * Test TenantDataBrowser Livewire component can create and delete tenant users.
     */
    public function test_tenant_data_browser_livewire_can_create_and_delete_users(): void
    {
        $userEmail = 'livewire-'.uniqid().'@apex.test';

        Livewire::actingAs($this->admin, 'admin')
            ->test(TenantDataBrowser::class, ['tenantId' => $this->tenant->id])
            ->assertStatus(200)
            ->call('openCreateUserModal')
            ->assertSet('showCreateUserModal', true)
            ->set('newUserName', 'Marcus Vance')
            ->set('newUserEmail', $userEmail)
            ->set('newUserPassword', 'SecurePassword!123')
            ->call('saveTenantUser')
            ->assertSet('showCreateUserModal', false)
            ->assertSet('feedbackType', 'success')
            ->assertSee($userEmail)
            ->assertSee('Marcus Vance');

        // Verify created in tenant database
        $createdId = null;
        $this->tenant->run(function () use ($userEmail, &$createdId) {
            $user = User::where('email', $userEmail)->first();
            $this->assertNotNull($user);
            $createdId = $user->id;
        });

        // Now delete via Livewire
        Livewire::actingAs($this->admin, 'admin')
            ->test(TenantDataBrowser::class, ['tenantId' => $this->tenant->id])
            ->call('deleteTenantUser', $createdId)
            ->assertSet('feedbackType', 'success')
            ->assertDontSee($userEmail);

        // Verify deleted from tenant partition
        $this->tenant->run(function () use ($userEmail) {
            $this->assertFalse(User::where('email', $userEmail)->exists());
        });
    }

    /**
     * Test unauthenticated access to tenant domain redirects to tenant login.
     */
    public function test_unauthenticated_tenant_route_redirects_to_tenant_login(): void
    {
        $response = $this->get("http://{$this->tenantDomain}/");

        $response->assertRedirect("http://{$this->tenantDomain}/login");
    }

    /**
     * Test tenant login page renders correctly on tenant subdomain.
     */
    public function test_tenant_login_page_renders_on_tenant_subdomain(): void
    {
        $response = $this->get("http://{$this->tenantDomain}/login");

        $response->assertStatus(200);
        $response->assertSee('Apex Technologies');
        $response->assertSee($this->tenant->id);
        $response->assertSee('Sign In to Tenant Portal');
    }

    /**
     * Test tenant portal supports dynamic locale switching and Arabic RTL layout.
     */
    public function test_tenant_locale_switching_and_arabic_rtl_rendering(): void
    {
        // Switch to Arabic via locale switch route
        $switchResponse = $this->get("http://{$this->tenantDomain}/locale/ar");
        $switchResponse->assertRedirect();
        $switchResponse->assertSessionHas('locale', 'ar');

        // Request login in Arabic
        $arResponse = $this->withSession(['locale' => 'ar'])->get("http://{$this->tenantDomain}/login");
        $arResponse->assertStatus(200);
        $arResponse->assertSee('dir="rtl"', false);
        $arResponse->assertSee('lang="ar"', false);
        $arResponse->assertSee('بوابة المستأجر');
        $arResponse->assertSee('تسجيل الدخول إلى بوابة المستأجر');

        // Request login in English
        $enResponse = $this->withSession(['locale' => 'en'])->get("http://{$this->tenantDomain}/login");
        $enResponse->assertStatus(200);
        $enResponse->assertSee('dir="ltr"', false);
        $enResponse->assertSee('lang="en"', false);
        $enResponse->assertSee('Sign In to Tenant Portal');
    }

    /**
     * Test tenant user can successfully authenticate and access tenant dashboard.
     */
    public function test_tenant_user_can_login_and_access_dashboard(): void
    {
        $loginEmail = "admin@{$this->tenant->id}.localhost";

        $response = $this->post("http://{$this->tenantDomain}/login", [
            'email' => $loginEmail,
            'password' => 'password',
        ]);

        $response->assertRedirect(rtrim("http://{$this->tenantDomain}", '/'));

        // Follow redirect or access dashboard directly
        $dashResponse = $this->get("http://{$this->tenantDomain}/");
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Welcome back, Apex Technologies!');
        $dashResponse->assertSee($this->tenant->id);
    }

    /**
     * Test tenant login rejects invalid credentials.
     */
    public function test_tenant_login_rejects_invalid_credentials(): void
    {
        $loginEmail = "admin@{$this->tenant->id}.localhost";

        $response = $this->from("http://{$this->tenantDomain}/login")->post("http://{$this->tenantDomain}/login", [
            'email' => $loginEmail,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect("http://{$this->tenantDomain}/login");
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test tenant user can logout.
     */
    public function test_tenant_user_can_logout(): void
    {
        $user = null;
        $this->tenant->run(function () use (&$user) {
            $user = User::where('email', "admin@{$this->tenant->id}.localhost")->first();
        });

        $this->actingAs($user, 'tenant');

        $response = $this->post("http://{$this->tenantDomain}/logout");

        $response->assertRedirect("http://{$this->tenantDomain}/login");
    }

    /**
     * Test tenant user cannot access central admin protected routes.
     */
    public function test_tenant_user_cannot_access_central_admin_routes(): void
    {
        $user = null;
        $this->tenant->run(function () use (&$user) {
            $user = User::where('email', "admin@{$this->tenant->id}.localhost")->first();
        });

        $response = $this->actingAs($user, 'tenant')->get('/admins');

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.login'));
    }
}
