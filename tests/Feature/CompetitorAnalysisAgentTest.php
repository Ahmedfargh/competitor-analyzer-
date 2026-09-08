<?php

namespace Tests\Feature;

use App\Ai\Agents\CompetitorAnalysisAgent;
use App\Ai\Tools\GooglePlacesTool;
use App\Ai\Tools\WebScraperTool;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Prompts\AgentPrompt;
use Laravel\Ai\Tools\Request as ToolRequest;
use Tests\TestCase;

class CompetitorAnalysisAgentTest extends TestCase
{
    /**
     * Test that the agent has the expected tools configured.
     */
    public function test_agent_tools_configuration(): void
    {
        $agent = new CompetitorAnalysisAgent;
        $tools = iterator_to_array($agent->tools());

        $this->assertCount(5, $tools);
        $toolClasses = array_map(fn ($t) => get_class($t), $tools);
        $this->assertContains(GooglePlacesTool::class, $toolClasses);
        $this->assertContains(WebScraperTool::class, $toolClasses);
    }

    /**
     * Test GooglePlacesTool queries Google Maps API and formats results.
     */
    public function test_google_places_tool_handles_mocked_search(): void
    {
        Http::fake([
            'maps.googleapis.com/*' => Http::response([
                'status' => 'OK',
                'results' => [
                    [
                        'name' => 'Stripe HQ',
                        'formatted_address' => '354 Oyster Point Blvd, South San Francisco, CA',
                        'rating' => 4.6,
                        'user_ratings_total' => 128,
                        'business_status' => 'OPERATIONAL',
                        'types' => ['point_of_interest', 'establishment'],
                    ],
                ],
            ], 200),
        ]);

        $tool = new GooglePlacesTool;
        $resultJson = $tool->handle(new ToolRequest([
            'query' => 'Stripe HQ',
        ]));

        $data = json_decode((string) $resultJson, true);

        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Stripe HQ', $data['query']);
        $this->assertCount(1, $data['places']);
        $this->assertEquals(4.6, $data['places'][0]['rating']);
        $this->assertEquals('354 Oyster Point Blvd, South San Francisco, CA', $data['places'][0]['formatted_address']);
    }

    /**
     * Test WebScraperTool extracts content from target URL.
     */
    public function test_web_scraper_tool_extracts_html(): void
    {
        Http::fake([
            'stripe.com/pricing' => Http::response('<html><head><title>Stripe Pricing &amp; Fees</title></head><body><h1>Pay-as-you-go pricing</h1><p>2.9% + 30¢ per successful card charge.</p></body></html>', 200),
        ]);

        $tool = new WebScraperTool;
        $resultJson = $tool->handle(new ToolRequest([
            'url' => 'https://stripe.com/pricing',
        ]));

        $data = json_decode((string) $resultJson, true);

        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Stripe Pricing & Fees', $data['title']);
        $this->assertStringContainsString('Pay-as-you-go pricing', $data['content_excerpt']);
    }

    /**
     * Test CompetitorAnalysisAgent with faked structured output.
     */
    public function test_competitor_analysis_agent_faked_structured_response(): void
    {
        CompetitorAnalysisAgent::fake([
            [
                'competitor_name' => 'Adyen',
                'domain' => 'adyen.com',
                'market_positioning' => 'Global enterprise omnichannel payments platform',
                'pricing_model' => 'Interchange++ pricing with volume tiers',
                'geographic_presence' => [
                    'branch_count_estimate' => 28,
                    'primary_locations' => ['Amsterdam', 'San Francisco', 'Singapore', 'London'],
                    'customer_rating_avg' => 4.4,
                    'sentiment_summary' => 'High enterprise reliability praise, steep minimum commitment barrier',
                ],
                'strengths' => ['Unified commerce platform', 'Single contract globally', 'Direct acquiring licenses'],
                'vulnerabilities' => ['High barriers for mid-market', 'Complex onboarding', 'Less self-serve developer focus'],
                'sales_battlecard' => 'Position our automated onboarding and transparent pay-as-you-go model against Adyen minimum volume requirements.',
                'tactical_countermoves' => ['Offer zero-minimum trial', 'Highlight instant developer sandbox'],
            ],
        ]);

        $agent = new CompetitorAnalysisAgent;
        $response = $agent->prompt('Analyze Adyen enterprise payments');

        $this->assertEquals('Adyen', $response['competitor_name']);
        $this->assertEquals('adyen.com', $response['domain']);
        $this->assertIsArray($response['strengths']);
        $this->assertCount(3, $response['strengths']);
        $this->assertEquals(4.4, $response['geographic_presence']['customer_rating_avg']);

        CompetitorAnalysisAgent::assertPrompted(function (AgentPrompt $prompt) {
            return str_contains($prompt->prompt, 'Adyen');
        });
    }

    /**
     * Test API endpoint for competitor analysis.
     */
    public function test_competitor_analysis_api_endpoint(): void
    {
        CompetitorAnalysisAgent::fake([
            [
                'competitor_name' => 'Checkout.com',
                'domain' => 'checkout.com',
                'market_positioning' => 'Cloud payments for digital businesses',
                'pricing_model' => 'Tailored custom interchange pricing',
                'geographic_presence' => [
                    'branch_count_estimate' => 19,
                    'primary_locations' => ['London', 'Dubai', 'Paris'],
                    'customer_rating_avg' => 4.5,
                    'sentiment_summary' => 'Strong regional MENA/Europe performance',
                ],
                'strengths' => ['Localized acquiring in MENA & Europe', 'Dedicated account managers'],
                'vulnerabilities' => ['Less mature US ecosystem', 'Opacity in public tier pricing'],
                'sales_battlecard' => 'Leverage our clear upfront pricing and instant self-serve API access.',
                'tactical_countermoves' => ['Target prospects seeking immediate setup without sales negotiation'],
            ],
        ]);

        $response = $this->postJson('/api/competitor-analysis', [
            'target' => 'Checkout.com',
            'domain' => 'checkout.com',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('target', 'Checkout.com')
            ->assertJsonPath('data.competitor_name', 'Checkout.com')
            ->assertJsonPath('data.geographic_presence.primary_locations.0', 'London');
    }
}
