<?php

namespace Tests\Feature;

use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Modules\Admin\Models\Admin;
use Tests\TestCase;

class AdminSubscriptionPlansTest extends TestCase
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
            ['email' => 'test-plan-admin@compitator.com'],
            [
                'name' => 'Plan Admin',
                'password' => bcrypt('password'),
            ]
        );
    }

    public function test_admin_can_view_plans_with_egp_rates(): void
    {
        SubscriptionPlan::create([
            'name' => 'Starter EGP Test',
            'slug' => 'starter-egp-test-'.uniqid(),
            'price_egp' => 1490.00,
            'price_usd' => 29.00,
            'billing_period' => 'monthly',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.plans.index'));

        $response->assertStatus(200);
        $response->assertSee('1,490');
        $response->assertSee('ج.م (EGP)');
        $response->assertSee('Starter EGP Test');
    }

    public function test_admin_can_create_new_plan_via_interface(): void
    {
        $slug = 'scale-'.uniqid();
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.plans.store'), [
            'name' => 'Scale Tier',
            'slug' => $slug,
            'description' => 'For hyper growth',
            'price_egp' => 4990.00,
            'price_usd' => 99.00,
            'billing_period' => 'monthly',
            'features' => ['VIP Support', 'Realtime AI'],
            'is_popular' => 1,
            'is_active' => 1,
            'sort_order' => 5,
        ]);

        $response->assertRedirect(route('admin.plans.index'));

        $this->assertDatabaseHas('subscription_plans', [
            'slug' => $slug,
            'price_egp' => 4990.00,
            'price_usd' => 99.00,
        ]);

        // Check activity log was recorded
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'plan.saved',
        ]);
    }

    public function test_admin_can_delete_plan(): void
    {
        $plan = SubscriptionPlan::create([
            'name' => 'To Delete',
            'slug' => 'to-delete-'.uniqid(),
            'price_egp' => 500.00,
            'price_usd' => 10.00,
            'billing_period' => 'monthly',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.plans.destroy', $plan->id));

        $response->assertRedirect(route('admin.plans.index'));
        $this->assertDatabaseMissing('subscription_plans', ['id' => $plan->id]);
    }

    public function test_plan_supports_spatie_translations_for_en_and_ar(): void
    {
        $plan = SubscriptionPlan::create([
            'name' => [
                'en' => 'Enterprise Plus',
                'ar' => 'باقة المؤسسات المتقدمة',
            ],
            'slug' => 'ent-plus-'.uniqid(),
            'description' => [
                'en' => 'English description',
                'ar' => 'وصف باللغة العربية',
            ],
            'price_egp' => 12990.00,
            'price_usd' => 259.00,
            'billing_period' => 'monthly',
            'features' => [
                'en' => ['Dedicated cluster', 'Custom models'],
                'ar' => ['عنقود سحابي مخصص', 'نماذج ذكاء اصطناعي مخصصة'],
            ],
        ]);

        $this->assertSame('Enterprise Plus', $plan->getTranslation('name', 'en'));
        $this->assertSame('باقة المؤسسات المتقدمة', $plan->getTranslation('name', 'ar'));

        app()->setLocale('ar');
        $this->assertSame('باقة المؤسسات المتقدمة', $plan->name);
        $this->assertSame('وصف باللغة العربية', $plan->description);
        $this->assertContains('عنقود سحابي مخصص', $plan->features);

        app()->setLocale('en');
        $this->assertSame('Enterprise Plus', $plan->name);
        $this->assertSame('English description', $plan->description);
        $this->assertContains('Dedicated cluster', $plan->features);
    }

    public function test_admin_can_create_plan_with_multilingual_data_via_http(): void
    {
        $slug = 'localized-'.uniqid();
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.plans.store'), [
            'name_en' => 'Global Plan',
            'name_ar' => 'الخطة العالمية',
            'slug' => $slug,
            'description_en' => 'Global reach for businesses',
            'description_ar' => 'وصول عالمي للأعمال',
            'price_egp' => 7990.00,
            'price_usd' => 159.00,
            'billing_period' => 'monthly',
            'features' => ['Global Proxies'],
        ]);

        $response->assertRedirect(route('admin.plans.index'));

        $plan = SubscriptionPlan::where('slug', $slug)->firstOrFail();
        $this->assertSame('Global Plan', $plan->getTranslation('name', 'en'));
        $this->assertSame('الخطة العالمية', $plan->getTranslation('name', 'ar'));
    }
}
