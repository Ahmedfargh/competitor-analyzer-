<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Modules\Admin\Models\Admin;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Tests\TestCase;

class AdminActivityLogTest extends TestCase
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
            ['email' => 'test-audit-admin@compitator.com'],
            [
                'name' => 'Audit Admin',
                'password' => bcrypt('password'),
            ]
        );
    }

    public function test_admin_can_view_activity_logs(): void
    {
        ActivityLog::create([
            'admin_id' => $this->admin->id,
            'action' => 'security.audit',
            'description' => 'Tested security credentials',
            'ip_address' => '127.0.0.1',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.activity-logs.index'));

        $response->assertStatus(200);
        $response->assertSee('security.audit');
        $response->assertSee('Tested security credentials');
    }

    public function test_activity_log_service_records_entries_properly(): void
    {
        $this->actingAs($this->admin, 'admin');

        $log = app(ActivityLogServiceInterface::class)->log(
            action: 'agent.mission_dispatched',
            description: 'Dispatched Gemini web scraper mission',
            properties: ['target' => 'https://competitor.com']
        );

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'agent.mission_dispatched',
            'description' => 'Dispatched Gemini web scraper mission',
        ]);
    }

    public function test_admin_observer_automatically_logs_activity(): void
    {
        $this->actingAs($this->admin, 'admin');

        $newAdmin = Admin::create([
            'name' => 'Observer Admin',
            'email' => 'observer-admin-'.uniqid().'@compitator.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'admin_user.created',
            'subject_type' => Admin::class,
            'subject_id' => (string) $newAdmin->id,
        ]);

        $newAdmin->update(['name' => 'Updated Observer Admin']);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'admin_user.updated',
            'subject_type' => Admin::class,
            'subject_id' => (string) $newAdmin->id,
        ]);

        $adminId = $newAdmin->id;
        $newAdmin->delete();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'admin_user.deleted',
            'subject_type' => Admin::class,
            'subject_id' => (string) $adminId,
        ]);
    }

    public function test_subscription_plan_observer_automatically_logs_activity(): void
    {
        $this->actingAs($this->admin, 'admin');

        $plan = SubscriptionPlan::create([
            'name' => 'Observer Plan',
            'slug' => 'obs-plan-'.uniqid(),
            'price_egp' => 1200.00,
            'price_usd' => 25.00,
            'billing_period' => 'monthly',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'plan.saved',
            'subject_type' => SubscriptionPlan::class,
            'subject_id' => (string) $plan->id,
        ]);

        $planId = $plan->id;
        $plan->delete();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'plan.deleted',
            'subject_type' => SubscriptionPlan::class,
            'subject_id' => (string) $planId,
        ]);
    }

    public function test_tenant_observer_automatically_logs_activity(): void
    {
        $this->actingAs($this->admin, 'admin');

        $tenantId = 'obs-tenant-'.uniqid();
        $tenant = Tenant::create([
            'id' => $tenantId,
            'company_name' => 'Observer Corp',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tenant.provisioned',
            'subject_type' => Tenant::class,
            'subject_id' => $tenantId,
        ]);

        $tenant->delete();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tenant.deprovisioned',
            'subject_type' => Tenant::class,
            'subject_id' => $tenantId,
        ]);
    }
}
