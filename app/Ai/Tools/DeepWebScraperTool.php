<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class DeepWebScraperTool implements Tool
{
    /**
     * Strategic paths and keywords to probe for comprehensive competitive intelligence.
     */
    protected array $strategicTargets = [
        'pricing' => ['pricing', 'prices', 'plans', 'tariffs', 'billing'],
        'features' => ['features', 'product', 'solutions', 'platform', 'capabilities'],
        'about' => ['about', 'company', 'our-story', 'team'],
        'changelog' => ['changelog', 'updates', 'releases', 'whats-new'],
    ];

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Performs multi-level deep web scraping of a competitor domain, automatically discovering and extracting key strategic sub-pages including Pricing tiers, Product Features, Company background, and Changelogs.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $targetInput = trim($request['url'] ?? $request['domain'] ?? '');

        if (empty($targetInput)) {
            return (string) json_encode([
                'status' => 'error',
                'message' => 'A valid URL or domain is required.',
            ]);
        }

        if (! filter_var($targetInput, FILTER_VALIDATE_URL)) {
            $rootUrl = 'https://'.ltrim($targetInput, '/');
        } else {
            $rootUrl = rtrim($targetInput, '/');
        }

        $parsedUrl = parse_url($rootUrl);
        $host = $parsedUrl['host'] ?? $rootUrl;
        $scheme = $parsedUrl['scheme'] ?? 'https';
        $baseUrl = "{$scheme}://{$host}";

        try {
            // Step 1: Fetch root homepage
            $homeResponse = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 CompitatorBot/2.0',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($baseUrl);

            $pagesToScrape = [];
            $homeHtml = $homeResponse->successful() ? $homeResponse->body() : '';

            // Step 2: Discover internal links from homepage matching strategic keywords
            if (! empty($homeHtml)) {
                $discoveredLinks = $this->extractInternalLinks($homeHtml, $baseUrl, $host);
                foreach ($this->strategicTargets as $category => $keywords) {
                    foreach ($discoveredLinks as $link) {
                        foreach ($keywords as $keyword) {
                            if (str_contains(strtolower($link), $keyword) && ! isset($pagesToScrape[$category])) {
                                $pagesToScrape[$category] = $link;
                                break 2;
                            }
                        }
                    }
                }
            }

            // Fallback: If not found in internal links, probe canonical paths
            foreach ($this->strategicTargets as $category => $keywords) {
                if (! isset($pagesToScrape[$category])) {
                    $pagesToScrape[$category] = "{$baseUrl}/{$category}";
                }
            }

            // Always include the homepage
            $pagesToScrape['homepage'] = $baseUrl;

            // Step 3: Fetch top strategic pages concurrently via Http::pool
            $responses = Http::pool(function ($pool) use ($pagesToScrape) {
                $requests = [];
                foreach ($pagesToScrape as $category => $url) {
                    $requests[$category] = $pool->as($category)->timeout(8)->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 CompitatorBot/2.0',
                    ])->get($url);
                }

                return $requests;
            });

            $scrapedResults = [];
            foreach ($pagesToScrape as $category => $url) {
                $response = $responses[$category] ?? null;
                if ($response && $response instanceof Response && $response->successful()) {
                    $html = $response->body();
                    $scrapedResults[$category] = [
                        'url' => $url,
                        'status' => 'success',
                        'title' => $this->extractTitle($html),
                        'excerpt' => $this->extractCleanText($html, 2000),
                        'pricing_signals' => $this->detectPricingSignals($html),
                    ];
                } else {
                    $scrapedResults[$category] = [
                        'url' => $url,
                        'status' => 'not_found_or_blocked',
                    ];
                }
            }

            return (string) json_encode([
                'status' => 'success',
                'target_host' => $host,
                'scraped_pages_count' => count(array_filter($scrapedResults, fn ($p) => ($p['status'] ?? '') === 'success')),
                'pages' => $scrapedResults,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return (string) json_encode([
                'status' => 'error',
                'target_host' => $host,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Extract internal hyperlinks from HTML body.
     */
    protected function extractInternalLinks(string $html, string $baseUrl, string $host): array
    {
        preg_match_all('/<a\s+[^>]*href=["\']([^"\']+)["\']/i', $html, $matches);
        $links = [];

        foreach ($matches[1] ?? [] as $href) {
            $href = trim($href);
            if (empty($href) || str_starts_with($href, '#') || str_starts_with($href, 'javascript:') || str_starts_with($href, 'mailto:')) {
                continue;
            }

            if (str_starts_with($href, '/')) {
                $links[] = rtrim($baseUrl, '/').$href;
            } elseif (str_starts_with($href, 'http')) {
                $linkHost = parse_url($href, PHP_URL_HOST);
                if ($linkHost === $host || str_ends_with((string) $linkHost, ".{$host}")) {
                    $links[] = $href;
                }
            }
        }

        return array_values(array_unique($links));
    }

    /**
     * Extract title tag from HTML.
     */
    protected function extractTitle(string $html): string
    {
        preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches);

        return trim(html_entity_decode($matches[1] ?? 'Untitled', ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    /**
     * Strip tags, scripts, and normalize text whitespace.
     */
    protected function extractCleanText(string $html, int $limit = 2000): string
    {
        $clean = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $clean = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', (string) $clean);
        $clean = strip_tags((string) $clean);
        $clean = preg_replace('/\s+/', ' ', (string) $clean);

        return mb_substr(trim((string) $clean), 0, $limit);
    }

    /**
     * Search for pricing markers, currencies, and tier keywords in HTML.
     */
    protected function detectPricingSignals(string $html): array
    {
        $signals = [];
        $lower = strtolower($html);

        $currencies = ['$', '€', '£', 'egp', 'usd', 'eur', 'sar', 'aed', 'ج.م', 'ر.س'];
        foreach ($currencies as $curr) {
            if (str_contains($lower, $curr)) {
                $signals['detected_currencies'][] = $curr;
            }
        }

        $tierKeywords = ['starter', 'basic', 'pro', 'professional', 'growth', 'business', 'enterprise', 'free trial', 'monthly', 'annually', 'custom pricing'];
        foreach ($tierKeywords as $tier) {
            if (str_contains($lower, $tier)) {
                $signals['tier_keywords'][] = $tier;
            }
        }

        $signals['detected_currencies'] = array_values(array_unique($signals['detected_currencies'] ?? []));
        $signals['tier_keywords'] = array_values(array_unique($signals['tier_keywords'] ?? []));

        return $signals;
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'url' => $schema->string()->description('The competitor target website URL or domain to deep scrape (e.g. "https://stripe.com" or "resend.com")')->required(),
        ];
    }
}
