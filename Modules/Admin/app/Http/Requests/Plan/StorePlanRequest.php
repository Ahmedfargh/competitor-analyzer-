<?php

namespace Modules\Admin\Http\Requests\Plan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Admin\DTOs\Contracts\SubscriptionPlanDTOInterface;
use Modules\Admin\DTOs\Plan\SubscriptionPlanDTO;
use Modules\Admin\Http\Requests\Contracts\StorePlanRequestInterface;

class StorePlanRequest extends FormRequest implements StorePlanRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $planId = $this->route('plan') ?? $this->route('id');

        return [
            'name' => ['required_without_all:name_en,name_ar'],
            'name_en' => ['nullable', 'string', 'max:100'],
            'name_ar' => ['nullable', 'string', 'max:100'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:subscription_plans,slug,'.$planId],
            'description' => ['nullable'],
            'description_en' => ['nullable', 'string', 'max:500'],
            'description_ar' => ['nullable', 'string', 'max:500'],
            'price_egp' => ['required', 'numeric', 'min:0'],
            'price_usd' => ['required', 'numeric', 'min:0'],
            'billing_period' => ['required', 'string', 'in:monthly,yearly,lifetime'],
            'features' => ['nullable'],
            'is_popular' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function getName(): string|array
    {
        if ($this->filled('name_en') || $this->filled('name_ar')) {
            return array_filter([
                'en' => (string) $this->input('name_en'),
                'ar' => (string) $this->input('name_ar'),
            ]);
        }

        return $this->input('name');
    }

    public function getSlug(): string
    {
        return (string) $this->validated('slug');
    }

    public function getDescription(): string|array|null
    {
        if ($this->filled('description_en') || $this->filled('description_ar')) {
            return array_filter([
                'en' => (string) $this->input('description_en'),
                'ar' => (string) $this->input('description_ar'),
            ]);
        }

        return $this->input('description');
    }

    public function getPriceEgp(): float
    {
        return (float) $this->validated('price_egp');
    }

    public function getPriceUsd(): float
    {
        return (float) $this->validated('price_usd');
    }

    public function toDTO(): SubscriptionPlanDTOInterface
    {
        $rawFeatures = $this->input('features', []);
        $features = is_string($rawFeatures)
            ? array_values(array_filter(array_map('trim', explode("\n", $rawFeatures))))
            : (array) $rawFeatures;

        return new SubscriptionPlanDTO(
            name: $this->getName(),
            slug: $this->getSlug(),
            description: $this->getDescription(),
            priceEgp: $this->getPriceEgp(),
            priceUsd: $this->getPriceUsd(),
            billingPeriod: (string) $this->validated('billing_period', 'monthly'),
            features: $features,
            isPopular: (bool) $this->boolean('is_popular'),
            isActive: (bool) $this->boolean('is_active', true),
            sortOrder: (int) $this->validated('sort_order', 0)
        );
    }
}
