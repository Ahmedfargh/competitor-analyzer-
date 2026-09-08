<?php

namespace Modules\Admin\DTOs\Contracts;

interface UpdateTenantDTOInterface
{
    public function getCompanyName(): ?string;

    public function getDomain(): ?string;

    public function getPlanId(): ?int;

    public function getMetadata(): ?array;

    public function getProductProfile(): ?array;

    public function toArray(): array;
}
