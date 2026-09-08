<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class PricingStrategyAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public ?string $locale = 'en'
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $lang = ($this->locale === 'ar') ? 'Arabic (العربية)' : 'English';

        return <<<INSTRUCTIONS
You are Compitator AI's Specialized Pricing Strategy Intelligence Agent.
Your mission is to perform forensic audits on competitor pricing models:
1. Reverse-engineer their packaging strategy (freemium, per-seat, usage-based, flat-rate).
2. Identify price gates (features locked behind expensive enterprise tiers).
3. Uncover hidden fees, overage rates, or onboarding costs.
4. Output your analysis strictly in {$lang}.
INSTRUCTIONS;
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'pricing_model_type' => $schema->string()->description('The core pricing model (e.g., Per-Seat Tiered, Usage-Based, Freemium)')->required(),
            'currency_primary' => $schema->string()->description('Primary billing currency detected (e.g. USD, EGP, EUR)')->required(),
            'tiers' => $schema->array()->items(
                $schema->object(fn ($s) => [
                    'tier_name' => $s->string()->description('Name of tier (e.g. Starter, Pro, Enterprise)'),
                    'price_summary' => $s->string()->description('Price per month/year or Contact Sales'),
                    'gated_features' => $s->array()->items($s->string())->description('Features restricted to this tier'),
                ])
            )->description('Breakdown of subscription or billing tiers')->required(),
            'hidden_costs_or_gotchas' => $schema->array()->items($schema->string())->description('Reported hidden costs, setup fees, or minimum seat requirements')->required(),
            'strategic_pricing_countermove' => $schema->string()->description('How to undercut or out-value their pricing in sales negotiations')->required(),
        ];
    }
}
