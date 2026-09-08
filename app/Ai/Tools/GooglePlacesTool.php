<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GooglePlacesTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search Google Maps Places API to discover competitor physical branches, office locations, customer ratings, review counts, operational status, and address details.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'];
        $apiKey = config('services.google_maps.key') ?? env('GOOGLE_MAPS_API_KEY');

        if (empty($apiKey)) {
            return (string) json_encode([
                'error' => 'Google Maps API key is not configured.',
            ]);
        }

        try {
            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/place/textsearch/json', [
                'query' => $query,
                'key' => $apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $results = collect($data['results'] ?? [])->take(5)->map(function ($place) {
                    return [
                        'name' => $place['name'] ?? null,
                        'formatted_address' => $place['formatted_address'] ?? null,
                        'rating' => $place['rating'] ?? null,
                        'user_ratings_total' => $place['user_ratings_total'] ?? 0,
                        'business_status' => $place['business_status'] ?? null,
                        'types' => $place['types'] ?? [],
                    ];
                })->values()->all();

                return (string) json_encode([
                    'status' => 'success',
                    'query' => $query,
                    'total_results_found' => count($data['results'] ?? []),
                    'places' => $results,
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }

            return (string) json_encode([
                'status' => 'api_error',
                'http_status' => $response->status(),
                'message' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            return (string) json_encode([
                'status' => 'exception',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('The competitor brand name, company, or store search query (e.g. "Stripe offices", "Starbucks Dubai")')->required(),
        ];
    }
}
