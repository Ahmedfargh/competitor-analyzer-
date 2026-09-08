<?php

namespace Modules\Admin\Http\Requests\Contracts;

use Modules\Admin\DTOs\Contracts\CreateTenantDTOInterface;

interface StoreTenantRequestInterface
{
    public function getTenantId(): string;

    public function getCompanyName(): string;

    public function getDomain(): string;

    public function getPlanId(): ?int;

    public function toDTO(): CreateTenantDTOInterface;
}
