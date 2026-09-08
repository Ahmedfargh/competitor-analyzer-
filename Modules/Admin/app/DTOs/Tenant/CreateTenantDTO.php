<?php

namespace Modules\Admin\DTOs\Tenant;

use Modules\Admin\DTOs\Contracts\CreateTenantDTOInterface;

class CreateTenantDTO implements CreateTenantDTOInterface
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public function __construct(
        public readonly string $tenantId,
        public readonly string $companyName,
        public readonly string $domain,
        public readonly ?int $planId = null,
        public readonly array $metadata = []
    ) {}

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getPlanId(): ?int
    {
        return $this->planId;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->tenantId,
            'company_name' => $this->companyName,
            'domain' => $this->domain,
            'plan_id' => $this->planId,
            'metadata' => $this->metadata,
        ];
    }
}
