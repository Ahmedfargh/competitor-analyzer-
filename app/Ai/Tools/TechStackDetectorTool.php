<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class TechStackDetectorTool implements Tool
{
    /**
     * Known technology signatures categorized by functional domain.
     */
    protected array $signatures = [
        'billing' => [
            'Stripe' => ['js.stripe.com', 'stripe-js', 'checkout.stripe.com'],
            'Paddle' => ['cdn.paddle.com', 'paddle.js'],
            'Chargebee' => ['js.chargebee.com'],
            'LemonSqueezy' => ['lemonsqueezy.com'],
            'Paymob' => ['paymobsolutions.com', 'accept.paymob.com'],
        ],
        'analytics' => [
            'Google Analytics 4' => ['googletagmanager.com/gtag/js', 'google-analytics.com'],
            'Mixpanel' => ['cdn.mxpnl.com', 'mixpanel.init'],
            'Segment' => ['cdn.segment.com/analytics.js', 'analytics.load('],
            'PostHog' => ['app.posthog.com', 'posthog.init'],
            'Hotjar' => ['static.hotjar.com', 'hjid'],
        ],
        'customer_support' => [
            'Intercom' => ['widget.intercom.io', 'intercomSettings'],
            'Crisp' => ['client.crisp.chat', '$crisp'],
            'Zendesk' => ['static.zdassets.com', 'ekr.zdassets.com'],
            'HubSpot' => ['js.hs-scripts.com', 'hs-script-loader'],
        ],
        'frontend_framework' => [
            'Next.js' => ['__NEXT_DATA__', '/_next/static/'],
            'Nuxt.js' => ['__NUXT__', '/_nuxt/'],
            'React' => ['data-reactroot', 'react-dom'],
            'Vue.js' => ['data-v-', 'vue.runtime'],
            'WordPress' => ['/wp-content/', '/wp-includes/'],
            'Webflow' => ['webflow.js', 'wf-page', 'data-wf-site'],
            'TailwindCSS' => ['tailwind', 'tw-'],
        ],
        'infrastructure_cdn' => [
            'Cloudflare' => ['cf-ray', 'cf-cache-status', 'cloudflare'],
            'Vercel' => ['x-vercel-id', 'x-vercel-cache'],
            'AWS CloudFront' => ['x-amz-cf-id', 'cloudfront'],
            'Fastly' => ['x-fastly-request-id'],
        ],
    ];

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Detects and fingerprints competitor technology stack, underlying infrastructure, analytics providers, payment gateways, and frontend frameworks from HTML headers, scripts, and meta tags.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $target = trim($request['url'] ?? $request['domain'] ?? '');

        if (empty($target)) {
            return (string) json_encode([
                'status' => 'error',
                'message' => 'Target URL or domain is required.',
            ]);
        }

        if (! filter_var($target, FILTER_VALIDATE_URL)) {
            $target = 'https://'.ltrim($target, '/');
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 CompitatorBot/2.0',
                ])
                ->get($target);

            $html = $response->successful() ? $response->body() : '';
            $headers = $response->headers();

            $detectedStack = [];
            $headerString = json_encode($headers);

            foreach ($this->signatures as $category => $techs) {
                foreach ($techs as $techName => $patterns) {
                    foreach ($patterns as $pattern) {
                        if (str_contains($html, $pattern) || str_contains($headerString, $pattern)) {
                            $detectedStack[$category][] = $techName;
                            break;
                        }
                    }
                }
                if (isset($detectedStack[$category])) {
                    $detectedStack[$category] = array_values(array_unique($detectedStack[$category]));
                }
            }

            $detectedCount = array_sum(array_map('count', $detectedStack));

            return (string) json_encode([
                'status' => 'success',
                'target' => $target,
                'detected_technologies_count' => $detectedCount,
                'stack_by_category' => $detectedStack,
                'strategic_summary' => $this->composeStrategicSummary($detectedStack),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return (string) json_encode([
                'status' => 'error',
                'target' => $target,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Compose a strategic commentary on the detected stack.
     */
    protected function composeStrategicSummary(array $stack): string
    {
        $summary = [];

        if (! empty($stack['billing'])) {
            $summary[] = 'Billing Gateway: '.implode(', ', $stack['billing']).' indicates digital self-serve payment flows.';
        }
        if (! empty($stack['frontend_framework'])) {
            $summary[] = 'Modern Frontend Architecture: '.implode(', ', $stack['frontend_framework']).'.';
        }
        if (! empty($stack['analytics'])) {
            $summary[] = 'Product Telemetry: '.implode(', ', $stack['analytics']).' indicates data-driven conversion tracking.';
        }
        if (! empty($stack['customer_support'])) {
            $summary[] = 'Customer Engagement: '.implode(', ', $stack['customer_support']).' in use.';
        }

        return empty($summary)
            ? 'Proprietary or obfuscated frontend infrastructure without exposed public SDK tags.'
            : implode(' ', $summary);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'url' => $schema->string()->description('Competitor target URL or domain to fingerprint (e.g. "https://linear.app" or "stripe.com")')->required(),
        ];
    }
}
