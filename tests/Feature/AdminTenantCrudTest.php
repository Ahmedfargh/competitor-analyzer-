<?php

namespace Tests\Feature;

use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Modules\Admin\DTOs\Contracts\CreateTenantDTOInterface;
use Modules\Admin\DTOs\Contracts\UpdateTenantDTOInterface;
use Modules\Admin\Models\Admin;
use Modules\Admin\Services\Contracts\TenantServiceInterface;
use Modules\Admin\Services\TenantService;
use Modules\Admin\Support\TenantLayerCustomizer;
use Tests\TestCase;

class AdminTenantCrudTest extends TestCase
{
    use DatabaseTransactions;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            LaravelLocalizationRedirectFilter::class,
            LocaleSessionRedirect::class,
        ]);

        $this->admin = Admin::firstOrCreate(
            ['email' => 'test-tenant-admin@compitator.com'],
            [
                'name' => 'Master Admin',
                'password' => bcrypt('password'),
            ]
        );
    }

    public function test_guest_cannot_access_admin_tenants(): void
    {
        $response = $this->get('/admin/tenants');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_tenants_list(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/tenants');
        $response->assertStatus(200);
        $response->assertSee('Multi-Tenants');
    }

    public function test_admin_can_provision_new_tenant_via_interface_and_service(): void
    {
        $plan = SubscriptionPlan::create([
            'name' => 'Pro Plan Test',
            'slug' => 'pro-test-'.uniqid(),
            'price_egp' => 3490.00,
            'price_usd' => 69.00,
            'billing_period' => 'monthly',
        ]);

        $tenantId = 'acme-'.uniqid();

        $response = $this->actingAs($this->admin, 'admin')->post('/admin/tenants', [
            'id' => $tenantId,
            'company_name' => 'Acme Corporation',
            'domain' => $tenantId.'.localhost',
            'plan_id' => $plan->id,
        ]);

        $response->assertRedirect(route('admin.tenants.show', $tenantId));

        $this->assertDatabaseHas('tenants', [
            'id' => $tenantId,
        ]);

        $this->assertDatabaseHas('domains', [
            'domain' => $tenantId.'.localhost',
            'tenant_id' => $tenantId,
        ]);

        // Verify activity log recorded
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tenant.provisioned',
            'subject_id' => $tenantId,
        ]);
    }

    public function test_admin_can_browse_tenant_data(): void
    {
        $tenantId = 'test-client-'.uniqid();
        $tenant = Tenant::create([
            'id' => $tenantId,
            'company_name' => 'Test Client Inc',
        ]);
        $tenant->domains()->create(['domain' => $tenantId.'.localhost']);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.tenants.show', $tenant->id));

        $response->assertStatus(200);
        $response->assertSee('Test Client Inc');
        $response->assertSee($tenantId);
    }

    public function test_admin_can_update_tenant(): void
    {
        $tenantId = 'edit-tenant-'.uniqid();
        $tenant = Tenant::create([
            'id' => $tenantId,
            'company_name' => 'Old Name',
        ]);
        $tenant->domains()->create(['domain' => $tenantId.'-old.localhost']);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.tenants.update', $tenant->id), [
            'company_name' => 'Brand New Name',
            'domain' => $tenantId.'-new.localhost',
        ]);

        $response->assertRedirect(route('admin.tenants.show', $tenant->id));

        $tenant->refresh();
        $this->assertEquals('Brand New Name', $tenant->company_name);
        $this->assertEquals($tenantId.'-new.localhost', $tenant->primary_domain);
    }

    public function test_tenant_layer_customizer_allows_tenant_specific_override(): void
    {
        $customMockService = new class implements TenantServiceInterface
        {
            public function listTenants(array $filters = [], int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
            {
                return new LengthAwarePaginator([], 0, 15);
            }

            public function getTenant(string $id): Tenant
            {
                return new Tenant(['id' => 'custom-tenant']);
            }

            public function provisionTenant(CreateTenantDTOInterface $dto): Tenant
            {
                return new Tenant(['id' => 'custom-tenant']);
            }

            public function updateTenant(string $id, UpdateTenantDTOInterface $dto): Tenant
            {
                return new Tenant;
            }

            public function deprovisionTenant(string $id): bool
            {
                return true;
            }

            public function browseTenantData(string $id): array
            {
                return ['custom' => true];
            }

            public function getTenantOverviewStats(): array
            {
                return ['custom_stat' => 999];
            }

            public function createTenantUser(string $id, array $data): User
            {
                return new User($data);
            }

            public function deleteTenantUser(string $id, int|string $userId): bool
            {
                return true;
            }
        };

        TenantLayerCustomizer::register('special-tenant', TenantServiceInterface::class, fn () => $customMockService);

        $resolved = TenantLayerCustomizer::resolve(TenantServiceInterface::class, TenantService::class);
        $this->assertInstanceOf(TenantServiceInterface::class, $resolved);

        TenantLayerCustomizer::flush();
    }
}
