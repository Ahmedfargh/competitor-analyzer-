<?php

namespace App\Http\Controllers;

use App\Ai\Agents\CompetitorAnalysisAgent;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CompetitorAnalysisController extends Controller
{
    /**
     * Run an autonomous competitor analysis mission via the AI Agent.
     */
    public function analyze(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'target' => ['required', 'string', 'max:255'],
            'domain' => ['nullable', 'string', 'max:255'],
            'locale' => ['nullable', 'string', 'in:ar,en'],
            'tenant_id' => ['nullable', 'string', 'max:100'],
        ]);

        $locale = $validated['locale']
            ?? session('locale')
            ?? (class_exists(LaravelLocalization::class) ? LaravelLocalization::getCurrentLocale() : null)
            ?? app()->getLocale()
            ?? 'en';

        // Synchronize application locale with session/request
        app()->setLocale($locale);

        $tenantId = $validated['tenant_id'] ?? (function_exists('tenant') && tenant() ? tenant('id') : null);
        $tenant = $tenantId ? Tenant::find($tenantId) : null;

        $langInstruction = ($locale === 'ar') ? 'باللغة العربية حصراً' : 'in English';
        $prompt = "Conduct a complete competitor intelligence analysis {$langInstruction} for: {$validated['target']}";
        if (! empty($validated['domain'])) {
            $prompt .= " (Website: {$validated['domain']})";
        }

        try {
            $agent = new CompetitorAnalysisAgent(locale: $locale, tenant: $tenant);
            $analysis = $agent->prompt($prompt);

            return response()->json([
                'status' => 'success',
                'locale' => $locale,
                'target' => $validated['target'],
                'tenant_id' => $tenant?->id,
                'tenant_product' => $tenant?->product_profile['product_name'] ?? null,
                'data' => $analysis->toArray(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
