<?php

namespace Modules\Admin\DTOs\Contracts;

interface CreateTenantDTOInterface
{
    public function getTenantId(): string;

    public function getCompanyName(): string;

    public function getDomain(): string;

    public function getPlanId(): ?int;

    public function getMetadata(): array;

    public function toArray(): array;
}
