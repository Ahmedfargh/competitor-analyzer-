<?php

namespace Modules\Admin\Http\Requests\Contracts;

use Modules\Admin\DTOs\Contracts\RoleDTOInterface;

interface UpdateRoleRequestInterface
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array;

    public function toDTO(): RoleDTOInterface;
}
