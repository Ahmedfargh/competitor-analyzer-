<?php

namespace Modules\Admin\DTOs\Tenant;

use Modules\Admin\DTOs\Contracts\UpdateTenantDTOInterface;

class UpdateTenantDTO implements UpdateTenantDTOInterface
{
    /**
     * @param  array<string, mixed>|null  $metadata
     */
    public function __construct(
        public readonly ?string $companyName = null,
        public readonly ?string $domain = null,
        public readonly ?int $planId = null,
        public readonly ?array $metadata = null,
        public readonly ?array $productProfile = null
    ) {}

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function getPlanId(): ?int
    {
        return $this->planId;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function getProductProfile(): ?array
    {
        return $this->productProfile;
    }

    public function toArray(): array
    {
        return array_filter([
            'company_name' => $this->companyName,
            'domain' => $this->domain,
            'plan_id' => $this->planId,
            'metadata' => $this->metadata,
            'product_profile' => $this->productProfile,
        ], fn ($value) => $value !== null);
    }
}
