<?php

namespace Tests\Feature;

use App\Ai\Agents\CompetitorAnalysisAgent;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use Tests\TestCase;

class TenantProductProfileAndGapAnalysisTest extends TestCase
{
    use DatabaseTransactions;

    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::updateOrCreate(
            ['id' => 'test-unique-acme-tenant'],
            [
                'company_name' => 'Acme Real Estate CRM',
                'product_profile' => [
                    'product_name' => 'Acme PropTech AI',
                    'value_proposition' => 'Specialized real estate CRM for MENA developers and brokers',
                    'pricing_summary' => '$49/mo flat fee with unlimited agents',
                    'key_differentiators' => [
                        'Direct WhatsApp Cloud API synchronization',
                        'Offline lead capture mode',
                        'Automated contract generation in Arabic and English',
                    ],
                    'target_icp' => 'Mid-sized real estate brokerages with 10-50 agents',
                ],
            ]
        );
    }

    public function test_tenant_model_manages_product_profile_correctly(): void
    {
        $profile = $this->tenant->product_profile;

        $this->assertEquals('Acme PropTech AI', $profile['product_name']);
        $this->assertEquals('Specialized real estate CRM for MENA developers and brokers', $profile['value_proposition']);
        $this->assertCount(3, $profile['key_differentiators']);
        $this->assertTrue($this->tenant->hasProductProfile());

        // Test update
        $this->tenant->updateProductProfile([
            'pricing_summary' => '$39/mo introductory tier',
        ]);

        $this->assertEquals('$39/mo introductory tier', $this->tenant->fresh()->product_profile['pricing_summary']);
        $this->assertEquals('Acme PropTech AI', $this->tenant->fresh()->product_profile['product_name']);
    }

    public function test_competitor_analysis_agent_injects_tenant_product_context_into_instructions(): void
    {
        $agent = new CompetitorAnalysisAgent(
            locale: 'en',
            tenant: $this->tenant
        );

        $instructions = (string) $agent->instructions();

        $this->assertStringContainsString('OUR PRODUCT PROFILE & TENANT CONTEXT', $instructions);
        $this->assertStringContainsString('Acme PropTech AI', $instructions);
        $this->assertStringContainsString('Direct WhatsApp Cloud API synchronization', $instructions);
        $this->assertStringContainsString('1-TO-1 GAP ANALYSIS DIRECTIVE', $instructions);
    }

    public function test_competitor_analysis_agent_schema_contains_head_to_head_comparison(): void
    {
        $agent = new CompetitorAnalysisAgent(locale: 'en');
        $schema = $agent->schema(new JsonSchemaTypeFactory);

        $this->assertArrayHasKey('head_to_head_comparison', $schema);
        $this->assertArrayHasKey('tech_stack', $schema);
        $this->assertArrayHasKey('customer_pain_points', $schema);
    }

    public function test_api_competitor_analysis_supports_tenant_id_context(): void
    {
        CompetitorAnalysisAgent::fake([
            [
                'competitor_name' => 'Salesforce Real Estate',
                'domain' => 'salesforce.com',
                'market_positioning' => 'Global enterprise cloud CRM',
                'pricing_model' => 'Expensive per-user tiered licensing',
                'tech_stack' => [
                    'billing_gateway' => 'Custom ERP Billing',
                    'analytics' => ['Google Analytics'],
                    'frontend' => ['Lightning Web Components'],
                    'infrastructure' => 'AWS / Proprietary Cloud',
                ],
                'geographic_presence' => [
                    'branch_count_estimate' => 50,
                    'primary_locations' => ['San Francisco', 'Dubai', 'London'],
                    'customer_rating_avg' => 4.3,
                    'sentiment_summary' => 'High enterprise prestige, criticized for high consulting costs',
                ],
                'strengths' => ['Massive ecosystem', 'Deep customization options'],
                'vulnerabilities' => ['Long implementation cycles', 'Prohibitive pricing for SMBs'],
                'customer_pain_points' => ['High setup fees', 'Overwhelming complexity for simple broker teams'],
                'sales_battlecard' => 'Contrast our instant 10-minute setup and flat pricing with Salesforce year-long implementation.',
                'tactical_countermoves' => ['Highlight out-of-the-box WhatsApp integration without third-party apps'],
                'landmine_questions' => ['Ask how many weeks of consultant training are required before our first lead is logged.'],
                'head_to_head_comparison' => [
                    'where_we_win' => [
                        'Instant WhatsApp Cloud sync included free',
                        'Flat fee pricing vs $150/user/mo',
                    ],
                    'where_they_win' => [
                        'Massive third-party AppExchange ecosystem',
                    ],
                    'pricing_differential' => 'Acme is $49/mo flat vs Salesforce $150/user/mo + setup costs.',
                    'custom_sales_pitch_against_them' => 'Stop paying for consulting hours you do not need. Acme gives MENA brokers immediate closing power.',
                    'deal_breaker_questions_for_prospect' => [
                        'Are you willing to wait 6 months for your team to adopt a CRM, or do you want to close deals today?',
                    ],
                ],
            ],
        ]);

        $response = $this->postJson('/api/competitor-analysis', [
            'target' => 'Salesforce Real Estate',
            'domain' => 'salesforce.com',
            'tenant_id' => $this->tenant->id,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('tenant_id', $this->tenant->id)
            ->assertJsonPath('tenant_product', 'Acme PropTech AI')
            ->assertJsonPath('data.head_to_head_comparison.pricing_differential', 'Acme is $49/mo flat vs Salesforce $150/user/mo + setup costs.');
    }
}
