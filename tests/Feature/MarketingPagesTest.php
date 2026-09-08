<?php

namespace Tests\Feature;

use Mcamara\LaravelLocalization\LaravelLocalization;
use Tests\TestCase;

class MarketingPagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        putenv(LaravelLocalization::ENV_ROUTE_KEY.'=en');
        $this->refreshApplication();
    }

    protected function tearDown(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);
        parent::tearDown();
    }

    /**
     * Test home landing page loads successfully.
     */
    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/en');

        $response->assertOk()
            ->assertSee('Compitator')
            ->assertSee('Transform Competitor Data Into')
            ->assertSee('Test Scraper & AI Extraction Engine');
    }

    /**
     * Test about page loads successfully.
     */
    public function test_about_page_returns_successful_response(): void
    {
        $response = $this->get('/en/about');

        $response->assertOk()
            ->assertSee('We Built the Eyes and Brains for')
            ->assertSee('Our Story & Vision', false)
            ->assertSee('Pages Scraped Daily');
    }

    /**
     * Test features page loads successfully.
     */
    public function test_features_page_returns_successful_response(): void
    {
        $response = $this->get('/en/features');

        $response->assertOk()
            ->assertSee('Anti-Bot Evasion Fleet')
            ->assertSee('Multi-Modal DOM Diffing')
            ->assertSee('Instant Sales Battlecards');
    }

    /**
     * Test pricing page loads successfully.
     */
    public function test_pricing_page_returns_successful_response(): void
    {
        $response = $this->get('/en/pricing');

        $response->assertOk()
            ->assertSee('Simple, Predictable')
            ->assertSee('Detailed Plan Comparison')
            ->assertSee('Growth & Scale', false);
    }

    /**
     * Test contact page loads successfully.
     */
    public function test_contact_page_returns_successful_response(): void
    {
        $response = $this->get('/en/contact');

        $response->assertOk()
            ->assertSee('Let\'s discuss your', false)
            ->assertSee('Request Strategy Demo')
            ->assertSee('intel@compitator.ai');
    }
}
