<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class WebScraperTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Scrapes and extracts visible content, page titles, headings, and pricing tables from a competitor web page URL.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $url = $request['url'];

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            $url = 'https://'.ltrim($url, '/');
        }

        try {
            $response = Http::timeout(12)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36 CompitatorBot/1.0',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($url);

            if (! $response->successful()) {
                return (string) json_encode([
                    'status' => 'http_error',
                    'url' => $url,
                    'status_code' => $response->status(),
                ]);
            }

            $html = $response->body();

            // Strip scripts, styles, and extra whitespace to extract readable text
            $cleanText = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
            $cleanText = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $cleanText);
            $cleanText = strip_tags($cleanText);
            $cleanText = preg_replace('/\s+/', ' ', $cleanText);
            $extractedExcerpt = mb_substr(trim($cleanText), 0, 4000);

            // Extract basic meta tags and title
            preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $titleMatches);
            $pageTitle = html_entity_decode($titleMatches[1] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');

            return (string) json_encode([
                'status' => 'success',
                'url' => $url,
                'title' => trim($pageTitle),
                'content_excerpt' => $extractedExcerpt,
                'content_length' => strlen($cleanText),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return (string) json_encode([
                'status' => 'error',
                'url' => $url,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'url' => $schema->string()->description('The competitor target URL to scrape (e.g. "https://stripe.com/pricing")')->required(),
        ];
    }
}
