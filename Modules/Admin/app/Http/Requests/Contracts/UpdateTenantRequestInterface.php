<?php

namespace Modules\Admin\Http\Requests\Contracts;

use Modules\Admin\DTOs\Contracts\UpdateTenantDTOInterface;

interface UpdateTenantRequestInterface
{
    public function getCompanyName(): ?string;

    public function getDomain(): ?string;

    public function getPlanId(): ?int;

    public function toDTO(): UpdateTenantDTOInterface;
}
