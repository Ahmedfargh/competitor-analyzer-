<?php

namespace Tests\Feature;

use App\Ai\Agents\CompetitorAnalysisAgent;
use App\Ai\Agents\PricingStrategyAgent;
use App\Ai\Agents\SalesBattlecardAgent;
use App\Ai\Tools\CustomerSentimentMinerTool;
use App\Ai\Tools\DeepWebScraperTool;
use App\Ai\Tools\TechStackDetectorTool;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Tools\Request;
use Tests\TestCase;

class CompetitorAgentDeepIntelligenceTest extends TestCase
{
    public function test_deep_web_scraper_tool_extracts_strategic_pages_and_pricing_signals(): void
    {
        Http::fake([
            'https://competitor.test' => Http::response('<html><head><title>Competitor Home</title></head><body><a href="/pricing">View Pricing Plans</a><p>Welcome to our platform</p></body></html>', 200),
            'https://competitor.test/pricing' => Http::response('<html><head><title>Pricing Tiers</title></head><body><h1>Starter $29/mo and Pro $99/mo</h1><p>Annual billing discount available</p></body></html>', 200),
            'https://competitor.test/features' => Http::response('<html><head><title>Features</title></head><body><p>AI automated reporting</p></body></html>', 200),
            'https://competitor.test/about' => Http::response('<html><head><title>About Us</title></head><body><p>Founded in 2020</p></body></html>', 200),
            '*' => Http::response('Not found', 404),
        ]);

        $tool = new DeepWebScraperTool;
        $resultJson = (string) $tool->handle(new Request(['url' => 'https://competitor.test']));
        $data = json_decode($resultJson, true);

        $this->assertEquals('success', $data['status']);
        $this->assertEquals('competitor.test', $data['target_host']);
        $this->assertArrayHasKey('pricing', $data['pages']);
        $this->assertEquals('Pricing Tiers', $data['pages']['pricing']['title']);
        $this->assertContains('$', $data['pages']['pricing']['pricing_signals']['detected_currencies']);
        $this->assertContains('starter', $data['pages']['pricing']['pricing_signals']['tier_keywords']);
    }

    public function test_tech_stack_detector_tool_fingerprints_technologies(): void
    {
        $mockHtml = <<<'HTML'
        <!DOCTYPE html>
        <html>
        <head>
            <script src="https://js.stripe.com/v3/"></script>
            <script src="https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js"></script>
        </head>
        <body>
            <div id="__NEXT_DATA__">{}</div>
            <div class="tw-bg-black">Content</div>
        </body>
        </html>
        HTML;

        Http::fake([
            'https://saas-target.test' => Http::response($mockHtml, 200, [
                'cf-ray' => '89abc12345-DXB',
            ]),
        ]);

        $tool = new TechStackDetectorTool;
        $resultJson = (string) $tool->handle(new Request(['url' => 'https://saas-target.test']));
        $data = json_decode($resultJson, true);

        $this->assertEquals('success', $data['status']);
        $this->assertGreaterThanOrEqual(3, $data['detected_technologies_count']);
        $this->assertContains('Stripe', $data['stack_by_category']['billing']);
        $this->assertContains('Mixpanel', $data['stack_by_category']['analytics']);
        $this->assertContains('Next.js', $data['stack_by_category']['frontend_framework']);
        $this->assertContains('Cloudflare', $data['stack_by_category']['infrastructure_cdn']);
    }

    public function test_customer_sentiment_miner_tool_extracts_grievances_and_angles(): void
    {
        $tool = new CustomerSentimentMinerTool;
        $resultJson = (string) $tool->handle(new Request([
            'competitor_name' => 'Acme Corp',
            'domain' => 'acme.test',
        ]));
        $data = json_decode($resultJson, true);

        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Acme Corp', $data['competitor']);
        $this->assertNotEmpty($data['primary_customer_grievances']);
        $this->assertNotEmpty($data['sales_exploitation_angle']);
        $this->assertArrayHasKey('sentiment_polarity', $data);
    }

    public function test_specialist_agents_instantiation_and_schemas(): void
    {
        $pricingAgent = new PricingStrategyAgent(locale: 'ar');
        $this->assertStringContainsString('Arabic', (string) $pricingAgent->instructions());

        $battlecardAgent = new SalesBattlecardAgent(locale: 'en');
        $this->assertStringContainsString('English', (string) $battlecardAgent->instructions());
    }

    public function test_competitor_analysis_agent_orchestrates_all_tools_and_extended_schema(): void
    {
        $agent = new CompetitorAnalysisAgent(locale: 'en');
        $tools = iterator_to_array($agent->tools());

        $this->assertCount(5, $tools);

        $toolClasses = array_map(fn ($t) => get_class($t), $tools);
        $this->assertContains(DeepWebScraperTool::class, $toolClasses);
        $this->assertContains(TechStackDetectorTool::class, $toolClasses);
        $this->assertContains(CustomerSentimentMinerTool::class, $toolClasses);
    }
}
