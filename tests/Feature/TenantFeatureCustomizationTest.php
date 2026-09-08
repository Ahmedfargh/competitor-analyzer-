<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\TenantFeatureCustomization;
use Database\Seeders\TenantFeatureCustomizationSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Modules\Admin\Services\Contracts\ActivityLogServiceInterface;
use Modules\Admin\Support\TenantLayerCustomizer;
use Modules\Tenant\Services\CustomTenantActivityLogService;
use Tests\TestCase;

interface DummyReportServiceInterface
{
    public function generate(): string;
}

class DefaultReportService implements DummyReportServiceInterface
{
    public function generate(): string
    {
        return 'standard-report';
    }
}

class CustomAcmeReportService implements DummyReportServiceInterface
{
    public function generate(): string
    {
        return 'acme-custom-report';
    }
}

class CustomBetaReportService implements DummyReportServiceInterface
{
    public function generate(): string
    {
        return 'beta-custom-report';
    }
}

class TenantFeatureCustomizationTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        TenantLayerCustomizer::flush();
        parent::tearDown();
    }

    public function test_tenant_resolves_alternative_class_from_database_by_namespace(): void
    {
        $tenantId = 'acme-'.uniqid();
        $tenant = Tenant::create([
            'id' => $tenantId,
            'company_name' => 'Acme Corporation',
        ]);

        TenantFeatureCustomization::create([
            'tenant_id' => $tenant->id,
            'base_class' => DummyReportServiceInterface::class,
            'alternative_class' => CustomAcmeReportService::class,
            'is_active' => true,
        ]);

        // Resolve via TenantLayerCustomizer with explicit tenant id
        $service = TenantLayerCustomizer::resolve(
            DummyReportServiceInterface::class,
            DefaultReportService::class,
            $tenant->id
        );

        $this->assertInstanceOf(CustomAcmeReportService::class, $service);
        $this->assertSame('acme-custom-report', $service->generate());

        // Resolve via Tenant model method
        $modelResolved = $tenant->resolveFeatureClass(
            DummyReportServiceInterface::class,
            DefaultReportService::class
        );

        $this->assertInstanceOf(CustomAcmeReportService::class, $modelResolved);
        $this->assertSame('acme-custom-report', $modelResolved->generate());
    }

    public function test_falls_back_to_default_class_when_customization_is_inactive(): void
    {
        $tenant = Tenant::create([
            'id' => 'inactive-'.uniqid(),
            'company_name' => 'Inactive Corp',
        ]);

        TenantFeatureCustomization::create([
            'tenant_id' => $tenant->id,
            'base_class' => DummyReportServiceInterface::class,
            'alternative_class' => CustomAcmeReportService::class,
            'is_active' => false,
        ]);

        $service = TenantLayerCustomizer::resolve(
            DummyReportServiceInterface::class,
            DefaultReportService::class,
            $tenant->id
        );

        $this->assertInstanceOf(DefaultReportService::class, $service);
        $this->assertSame('standard-report', $service->generate());
    }

    public function test_falls_back_to_default_when_no_customization_row_exists(): void
    {
        $tenant = Tenant::create([
            'id' => 'plain-'.uniqid(),
            'company_name' => 'Plain Corp',
        ]);

        $service = TenantLayerCustomizer::resolve(
            DummyReportServiceInterface::class,
            DefaultReportService::class,
            $tenant->id
        );

        $this->assertInstanceOf(DefaultReportService::class, $service);
        $this->assertSame('standard-report', $service->generate());
    }

    public function test_multiple_tenants_resolve_their_own_alternative_classes(): void
    {
        $tenantA = Tenant::create(['id' => 'tenant-a-'.uniqid(), 'company_name' => 'Tenant A']);
        $tenantB = Tenant::create(['id' => 'tenant-b-'.uniqid(), 'company_name' => 'Tenant B']);
        $tenantC = Tenant::create(['id' => 'tenant-c-'.uniqid(), 'company_name' => 'Tenant C']);

        TenantFeatureCustomization::create([
            'tenant_id' => $tenantA->id,
            'base_class' => DummyReportServiceInterface::class,
            'alternative_class' => CustomAcmeReportService::class,
            'is_active' => true,
        ]);

        TenantFeatureCustomization::create([
            'tenant_id' => $tenantB->id,
            'base_class' => DummyReportServiceInterface::class,
            'alternative_class' => CustomBetaReportService::class,
            'is_active' => true,
        ]);

        $serviceA = $tenantA->resolveFeatureClass(DummyReportServiceInterface::class, DefaultReportService::class);
        $serviceB = $tenantB->resolveFeatureClass(DummyReportServiceInterface::class, DefaultReportService::class);
        $serviceC = $tenantC->resolveFeatureClass(DummyReportServiceInterface::class, DefaultReportService::class);

        $this->assertInstanceOf(CustomAcmeReportService::class, $serviceA);
        $this->assertInstanceOf(CustomBetaReportService::class, $serviceB);
        $this->assertInstanceOf(DefaultReportService::class, $serviceC);

        $this->assertSame('acme-custom-report', $serviceA->generate());
        $this->assertSame('beta-custom-report', $serviceB->generate());
        $this->assertSame('standard-report', $serviceC->generate());
    }

    public function test_tenant_feature_customization_observer_logs_activity(): void
    {
        $tenant = Tenant::create(['id' => 'logged-'.uniqid(), 'company_name' => 'Logged Corp']);

        $customization = TenantFeatureCustomization::create([
            'tenant_id' => $tenant->id,
            'base_class' => DummyReportServiceInterface::class,
            'alternative_class' => CustomAcmeReportService::class,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tenant.feature_customized',
            'subject_type' => TenantFeatureCustomization::class,
            'subject_id' => (string) $customization->id,
        ]);

        $customization->update([
            'alternative_class' => CustomBetaReportService::class,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tenant.feature_updated',
            'subject_type' => TenantFeatureCustomization::class,
            'subject_id' => (string) $customization->id,
        ]);

        $customization->delete();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'tenant.feature_removed',
            'subject_type' => TenantFeatureCustomization::class,
            'subject_id' => (string) $customization->id,
        ]);
    }

    public function test_tenant_feature_customization_uses_cache_and_invalidates_on_model_events(): void
    {
        $tenantId = 'cached-'.uniqid();
        $tenant = Tenant::create(['id' => $tenantId, 'company_name' => 'Cached Corp']);

        $customization = TenantFeatureCustomization::create([
            'tenant_id' => $tenant->id,
            'base_class' => DummyReportServiceInterface::class,
            'alternative_class' => CustomAcmeReportService::class,
            'is_active' => true,
        ]);

        // Resolving should populate the cache
        $service = TenantLayerCustomizer::resolve(
            DummyReportServiceInterface::class,
            DefaultReportService::class,
            $tenant->id
        );
        $this->assertInstanceOf(CustomAcmeReportService::class, $service);
        $this->assertTrue(Cache::has("tenant_customizations:{$tenant->id}"));

        $cachedMap = Cache::get("tenant_customizations:{$tenant->id}");
        $this->assertArrayHasKey(DummyReportServiceInterface::class, $cachedMap);
        $this->assertSame(CustomAcmeReportService::class, $cachedMap[DummyReportServiceInterface::class]);

        // Updating the customization should automatically invalidate the cache
        $customization->update([
            'alternative_class' => CustomBetaReportService::class,
        ]);

        $this->assertFalse(Cache::has("tenant_customizations:{$tenant->id}"));

        // Resolving again should reload the new class and repopulate the cache
        $updatedService = TenantLayerCustomizer::resolve(
            DummyReportServiceInterface::class,
            DefaultReportService::class,
            $tenant->id
        );
        $this->assertInstanceOf(CustomBetaReportService::class, $updatedService);
        $this->assertTrue(Cache::has("tenant_customizations:{$tenant->id}"));

        // Deleting the customization should also invalidate the cache
        $customization->delete();
        $this->assertFalse(Cache::has("tenant_customizations:{$tenant->id}"));

        $fallbackService = TenantLayerCustomizer::resolve(
            DummyReportServiceInterface::class,
            DefaultReportService::class,
            $tenant->id
        );
        $this->assertInstanceOf(DefaultReportService::class, $fallbackService);
    }

    public function test_tenant_feature_customization_seeder_dynamically_seeds_mappings_and_resolves(): void
    {
        $tenantId = 'dynamic-seeder-'.uniqid();
        $tenant = Tenant::create(['id' => $tenantId, 'company_name' => 'Dynamic Seeder Corp']);

        // Run dynamic seeder for specific tenant
        TenantFeatureCustomizationSeeder::seedForTenant($tenant->id, [
            ActivityLogServiceInterface::class => CustomTenantActivityLogService::class,
            DummyReportServiceInterface::class => CustomBetaReportService::class,
        ]);

        $this->assertDatabaseHas('tenant_feature_customizations', [
            'tenant_id' => $tenant->id,
            'base_class' => ActivityLogServiceInterface::class,
            'alternative_class' => CustomTenantActivityLogService::class,
            'is_active' => true,
        ]);

        // Resolving through tenant model
        $resolvedActivityService = $tenant->resolveFeatureClass(
            ActivityLogServiceInterface::class
        );
        $this->assertInstanceOf(CustomTenantActivityLogService::class, $resolvedActivityService);

        $resolvedDummy = $tenant->resolveFeatureClass(
            DummyReportServiceInterface::class,
            DefaultReportService::class
        );
        $this->assertInstanceOf(CustomBetaReportService::class, $resolvedDummy);
        $this->assertSame('beta-custom-report', $resolvedDummy->generate());
    }
}
