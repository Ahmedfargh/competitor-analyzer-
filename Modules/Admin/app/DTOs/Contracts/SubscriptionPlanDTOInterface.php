<?php

namespace Modules\Admin\DTOs\Contracts;

interface SubscriptionPlanDTOInterface
{
    public function getName(): string|array;

    public function getSlug(): string;

    public function getDescription(): string|array|null;

    public function getPriceEgp(): float;

    public function getPriceUsd(): float;

    public function getBillingPeriod(): string;

    public function getFeatures(): array;

    public function isPopular(): bool;

    public function isActive(): bool;

    public function getSortOrder(): int;

    public function toArray(): array;
}
