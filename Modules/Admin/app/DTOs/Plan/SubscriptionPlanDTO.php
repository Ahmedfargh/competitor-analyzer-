<?php

namespace Modules\Admin\DTOs\Plan;

use Modules\Admin\DTOs\Contracts\SubscriptionPlanDTOInterface;

class SubscriptionPlanDTO implements SubscriptionPlanDTOInterface
{
    /**
     * @param  string|array<string, string>  $name
     * @param  string|array<string, string>|null  $description
     * @param  array<mixed>  $features
     */
    public function __construct(
        public readonly string|array $name,
        public readonly string $slug,
        public readonly string|array|null $description = null,
        public readonly float $priceEgp = 0.0,
        public readonly float $priceUsd = 0.0,
        public readonly string $billingPeriod = 'monthly',
        public readonly array $features = [],
        public readonly bool $isPopular = false,
        public readonly bool $isActive = true,
        public readonly int $sortOrder = 0
    ) {}

    public function getName(): string|array
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDescription(): string|array|null
    {
        return $this->description;
    }

    public function getPriceEgp(): float
    {
        return $this->priceEgp;
    }

    public function getPriceUsd(): float
    {
        return $this->priceUsd;
    }

    public function getBillingPeriod(): string
    {
        return $this->billingPeriod;
    }

    public function getFeatures(): array
    {
        return $this->features;
    }

    public function isPopular(): bool
    {
        return $this->isPopular;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price_egp' => $this->priceEgp,
            'price_usd' => $this->priceUsd,
            'billing_period' => $this->billingPeriod,
            'features' => $this->features,
            'is_popular' => $this->isPopular,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
        ];
    }
}
