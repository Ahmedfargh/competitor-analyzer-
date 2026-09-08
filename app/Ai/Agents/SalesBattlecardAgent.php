<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class SalesBattlecardAgent implements Agent, HasStructuredOutput
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
You are Compitator AI's Elite Sales Battlecard & Competitive Win Strategist.
Your mission is to equip sales reps and founders to win deals against a specific competitor:
1. Synthesize competitor weaknesses, tech stack limitations, and customer complaints into lethal sales arguments.
2. Provide objection-handling scripts ("When prospect says X, reply with Y").
3. Craft "Landmine Questions" for prospects to ask the competitor that reveal their structural flaws.
4. Output your analysis strictly in {$lang}.
INSTRUCTIONS;
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'elevator_pitch_against_competitor' => $schema->string()->description('A punchy 30-second sales pitch on why to choose us over this competitor')->required(),
            'top_three_reasons_we_win' => $schema->array()->items($schema->string())->description('3 definitive competitive advantages we hold')->required(),
            'prospect_landmine_questions' => $schema->array()->items($schema->string())->description('Trap questions for the prospect to ask the competitor during demos')->required(),
            'objection_handling_scripts' => $schema->array()->items(
                $schema->object(fn ($s) => [
                    'prospect_claim' => $s->string()->description('E.g. "Competitor X is cheaper" or "Competitor X is bigger"'),
                    'tactical_response' => $s->string()->description('The exact scripted rebuttal for the sales rep'),
                ])
            )->description('Scripts for overcoming pro-competitor objections')->required(),
            'deal_killer_summary' => $schema->string()->description('The single strongest vulnerability that makes enterprise buyers switch')->required(),
        ];
    }
}
