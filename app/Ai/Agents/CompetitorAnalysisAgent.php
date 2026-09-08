<?php

namespace App\Ai\Agents;

use App\Ai\Tools\CustomerSentimentMinerTool;
use App\Ai\Tools\DeepWebScraperTool;
use App\Ai\Tools\GooglePlacesTool;
use App\Ai\Tools\TechStackDetectorTool;
use App\Ai\Tools\WebScraperTool;
use App\Models\Tenant;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\ProviderTool;
use Stringable;

class CompetitorAnalysisAgent implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;

    /**
     * Create a new agent instance with session locale and optional tenant product context.
     */
    public function __construct(
        public ?string $locale = null,
        public ?Tenant $tenant = null,
        public ?array $tenantProductProfile = null
    ) {
        $this->locale = $this->locale ?: app()->getLocale();
        if ($this->tenant && empty($this->tenantProductProfile)) {
            $this->tenantProductProfile = $this->tenant->product_profile;
        }
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $languageName = ($this->locale === 'ar') ? 'Arabic (العربية)' : 'English';

        $tenantSection = '';
        if (! empty($this->tenantProductProfile)) {
            $profile = $this->tenantProductProfile;
            $name = $profile['product_name'] ?? 'Our Solution';
            $valProp = $profile['value_proposition'] ?? 'Market-leading specialized platform';
            $pricing = $profile['pricing_summary'] ?? 'Competitive tiered pricing';
            $differentiators = implode(', ', (array) ($profile['key_differentiators'] ?? []));
            $icp = $profile['target_icp'] ?? 'Target market enterprise and SMB buyers';

            $tenantSection = <<<TENANT

=== OUR PRODUCT PROFILE & TENANT CONTEXT ===
- Product Name: {$name}
- Core Value Proposition: {$valProp}
- Pricing Structure: {$pricing}
- Key Differentiators: {$differentiators}
- Target Ideal Customer Profile (ICP): {$icp}

CRITICAL 1-TO-1 GAP ANALYSIS DIRECTIVE:
You are not analyzing in a vacuum. You are representing the company above.
In the head_to_head_comparison section, directly compare the competitor's capabilities against OUR PRODUCT profile. Highlight where we beat them, where they currently lead, and how our sales reps should pitch against them to win deals.
TENANT;
        }

        return <<<INSTRUCTIONS
You are Compitator AI's Master Market Intelligence Agent.
Your mission is to perform forensic, multi-dimensional competitor intelligence combining deep web scraping, infrastructure fingerprinting, physical market presence, customer sentiment mining, and 1-to-1 product gap analysis.

CRITICAL LANGUAGE REQUIREMENT:
The user's active session language is: {$languageName}.
You MUST generate the entire analysis and all output fields (competitor_name, market_positioning, pricing_model, strengths, vulnerabilities, sales_battlecard, tactical_countermoves, sentiment_summary, head_to_head_comparison, etc.) strictly in {$languageName}. Do not respond in English if the requested language is Arabic.
{$tenantSection}

When given a competitor name, domain, or market sector:
1. Use the DeepWebScraperTool to discover and scrape sub-pages (/pricing, /features, /about, /changelog).
2. Use the TechStackDetectorTool to fingerprint their cloud, billing (Stripe/Paddle), analytics, and frontend infrastructure.
3. Use the CustomerSentimentMinerTool and GooglePlacesTool to evaluate customer complaints, review sentiment, and physical presence.
4. Synthesize everything into an actionable competitive dossier, head-to-head gap analysis, and tactical sales battlecard fully written in {$languageName}.
INSTRUCTIONS;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return list<Agent|Tool|ProviderTool>
     */
    public function tools(): iterable
    {
        return [
            new DeepWebScraperTool,
            new TechStackDetectorTool,
            new CustomerSentimentMinerTool,
            new GooglePlacesTool,
            new WebScraperTool,
        ];
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'competitor_name' => $schema->string()->description('The official name of the analyzed competitor')->required(),
            'domain' => $schema->string()->description('Target website or domain')->required(),
            'market_positioning' => $schema->string()->description('How the competitor positions themselves in the market')->required(),
            'pricing_model' => $schema->string()->description('Pricing strategy summary (e.g. freemium, usage-based, tiered subscription)')->required(),
            'tech_stack' => $schema->object(fn ($s) => [
                'billing_gateway' => $s->string()->description('Identified billing or payment provider (e.g. Stripe, Paddle)'),
                'analytics' => $s->array()->items($s->string())->description('Detected telemetry and analytics tools'),
                'frontend' => $s->array()->items($s->string())->description('Detected UI frameworks or CMS'),
                'infrastructure' => $s->string()->description('Cloud or CDN provider (e.g. Cloudflare, AWS)'),
            ])->description('Fingerprinted technology infrastructure')->required(),
            'geographic_presence' => $schema->object(fn ($schema) => [
                'branch_count_estimate' => $schema->integer()->description('Estimated number of offices or physical locations identified'),
                'primary_locations' => $schema->array()->items($schema->string())->description('Key cities or regions of operation'),
                'customer_rating_avg' => $schema->number()->description('Average customer satisfaction or rating from Google Places'),
                'sentiment_summary' => $schema->string()->description('Summary of customer sentiment from reviews and ratings'),
            ])->description('Physical and regional footprint intelligence from Google Maps'),
            'strengths' => $schema->array()->items($schema->string())->description('Key competitor strengths and market moats')->required(),
            'vulnerabilities' => $schema->array()->items($schema->string())->description('Critical competitor weaknesses and gaps to exploit')->required(),
            'customer_pain_points' => $schema->array()->items($schema->string())->description('Primary user complaints and churn drivers identified from sentiment analysis')->required(),
            'sales_battlecard' => $schema->string()->description('A concise 30-second sales pitch against this competitor')->required(),
            'tactical_countermoves' => $schema->array()->items($schema->string())->description('Specific actionable maneuvers to win deals against them')->required(),
            'landmine_questions' => $schema->array()->items($schema->string())->description('Trap questions for prospective buyers to ask this competitor during demos')->required(),
            'head_to_head_comparison' => $schema->object(fn ($s) => [
                'where_we_win' => $s->array()->items($s->string())->description('Specific capabilities and value where our product wins over this competitor'),
                'where_they_win' => $s->array()->items($s->string())->description('Areas or features where the competitor currently holds an edge'),
                'pricing_differential' => $s->string()->description('Direct contrast between our pricing structure and theirs'),
                'custom_sales_pitch_against_them' => $s->string()->description('Tailored sales pitch positioning our product against them for our target ICP'),
                'deal_breaker_questions_for_prospect' => $s->array()->items($s->string())->description('Questions prospects should ask to highlight our advantages over them'),
            ])->description('1-to-1 Gap Analysis contrasting competitor with our tenant product profile')->required(),
        ];
    }
}
