<?php

namespace Tests\Feature;

use Mcamara\LaravelLocalization\LaravelLocalization;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    protected function tearDown(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);
        parent::tearDown();
    }

    public function test_english_locale_route(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY.'=en');
        $this->refreshApplication();

        $response = $this->get('/en');
        $response->assertOk()
            ->assertSee('lang="en"', false)
            ->assertSee('dir="ltr"', false)
            ->assertSee('Transform Competitor Data Into');
    }

    public function test_arabic_locale_route(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY.'=ar');
        $this->refreshApplication();

        $response = $this->get('/ar');
        $response->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false)
            ->assertSee('كومبيتاتور')
            ->assertSee('الرئيسية')
            ->assertSee('المميزات')
            ->assertSee('الأسعار');
    }

    public function test_root_redirects_to_localized_url(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);
        $this->refreshApplication();

        $response = $this->get('/');
        $response->assertRedirect();
    }

    public function test_arabic_subpages_render_rtl(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY.'=ar');
        $this->refreshApplication();

        $pages = ['/ar/about', '/ar/features', '/ar/pricing', '/ar/contact'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertOk()
                ->assertSee('lang="ar"', false)
                ->assertSee('dir="rtl"', false);
        }
    }

    public function test_page_contains_language_switcher(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY.'=en');
        $this->refreshApplication();

        $response = $this->get('/en');
        $response->assertOk()
            ->assertSee('/ar')
            ->assertSee('/en');
    }
}
