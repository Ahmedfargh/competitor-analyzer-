<?php

namespace Tests\Feature;

use App\Livewire\Admin\ActivityLogs\ActivityLogFeed;
use App\Livewire\Admin\DashboardOverview;
use App\Livewire\Admin\Plans\PlanManager;
use App\Livewire\Admin\Tenants\TenantDataBrowser;
use App\Livewire\Admin\Tenants\TenantManager;
use App\Models\ActivityLog;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Modules\Admin\Models\Admin;
use Tests\TestCase;

class AdminLivewireComponentsTest extends TestCase
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
            ['email' => 'livewire-admin@compitator.com'],
            [
                'name' => 'Livewire Admin',
                'password' => bcrypt('password'),
            ]
        );
    }

    public function test_dashboard_overview_livewire_renders_successfully(): void
    {
        Livewire::actingAs($this->admin, 'admin')
            ->test(DashboardOverview::class)
            ->assertStatus(200)
            ->assertSee($this->admin->name)
            ->assertSee('100%');
    }

    public function test_tenant_manager_livewire_provisions_tenant(): void
    {
        $tenantId = 'lw-tenant-'.uniqid();

        Livewire::actingAs($this->admin, 'admin')
            ->test(TenantManager::class)
            ->call('openCreateModal')
            ->set('tenant_id', $tenantId)
            ->set('company_name', 'Livewire Corp')
            ->set('domain', $tenantId.'.localhost')
            ->call('provisionTenant')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tenants', ['id' => $tenantId]);
        $this->assertDatabaseHas('domains', ['domain' => $tenantId.'.localhost']);
    }

    public function test_tenant_manager_livewire_searches_and_deletes(): void
    {
        $tenantId = 'to-delete-'.uniqid();
        $tenant = Tenant::create([
            'id' => $tenantId,
            'company_name' => 'Temporary Corp',
        ]);
        $tenant->domains()->create(['domain' => $tenantId.'.localhost']);

        Livewire::actingAs($this->admin, 'admin')
            ->test(TenantManager::class)
            ->set('search', $tenantId)
            ->assertSee('Temporary Corp')
            ->call('deleteTenant', $tenantId);

        $this->assertDatabaseMissing('tenants', ['id' => $tenantId]);
    }

    public function test_tenant_data_browser_renders_live(): void
    {
        $tenantId = 'browse-tenant-'.uniqid();
        $tenant = Tenant::create([
            'id' => $tenantId,
            'company_name' => 'Data Browser Inc',
        ]);
        $tenant->domains()->create(['domain' => $tenantId.'.localhost']);

        Livewire::actingAs($this->admin, 'admin')
            ->test(TenantDataBrowser::class, ['tenantId' => $tenantId])
            ->assertStatus(200)
            ->assertSee('Data Browser Inc')
            ->assertSee($tenantId);
    }

    public function test_plan_manager_currency_toggle_and_save(): void
    {
        $slug = 'lw-plan-'.uniqid();

        Livewire::actingAs($this->admin, 'admin')
            ->test(PlanManager::class)
            ->assertSet('currency', 'egp')
            ->call('setCurrency', 'usd')
            ->assertSet('currency', 'usd')
            ->call('openCreateModal')
            ->set('name', 'Livewire Growth Tier')
            ->set('slug', $slug)
            ->set('price_egp', 2490.00)
            ->set('price_usd', 49.00)
            ->set('billing_period', 'monthly')
            ->call('savePlan')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('subscription_plans', ['slug' => $slug]);
    }

    public function test_plan_manager_saves_multilingual_data_with_spatie_translations(): void
    {
        $slug = 'lw-multi-'.uniqid();

        Livewire::actingAs($this->admin, 'admin')
            ->test(PlanManager::class)
            ->call('openCreateModal')
            ->set('name_en', 'Livewire Prime')
            ->set('name_ar', 'لايف واير برايم')
            ->set('slug', $slug)
            ->set('description_en', 'Livewire prime description')
            ->set('description_ar', 'وصف لايف واير برايم')
            ->set('features_en', "Feature 1\nFeature 2")
            ->set('features_ar', "الميزة 1\nالميزة 2")
            ->set('price_egp', 3990.00)
            ->set('price_usd', 79.00)
            ->set('billing_period', 'monthly')
            ->call('savePlan')
            ->assertHasNoErrors();

        $plan = SubscriptionPlan::where('slug', $slug)->firstOrFail();
        $this->assertSame('Livewire Prime', $plan->getTranslation('name', 'en'));
        $this->assertSame('لايف واير برايم', $plan->getTranslation('name', 'ar'));
        $this->assertSame('Livewire prime description', $plan->getTranslation('description', 'en'));
        $this->assertSame('وصف لايف واير برايم', $plan->getTranslation('description', 'ar'));
    }

    public function test_activity_log_feed_filters_and_renders(): void
    {
        ActivityLog::create([
            'admin_id' => $this->admin->id,
            'action' => 'test.livewire_action',
            'description' => 'Tested livewire feed filter',
            'ip_address' => '127.0.0.1',
        ]);

        Livewire::actingAs($this->admin, 'admin')
            ->test(ActivityLogFeed::class)
            ->assertStatus(200)
            ->assertSee('test.livewire_action')
            ->assertSee('Tested livewire feed filter')
            ->set('actionFilter', 'test.livewire_action')
            ->assertSee('Tested livewire feed filter');
    }
}
