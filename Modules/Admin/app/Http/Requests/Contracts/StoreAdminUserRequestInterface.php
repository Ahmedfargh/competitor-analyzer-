<?php

namespace Modules\Admin\Http\Requests\Contracts;

use Modules\Admin\DTOs\Contracts\AdminUserDTOInterface;

interface StoreAdminUserRequestInterface
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array;

    public function toDTO(): AdminUserDTOInterface;
}
