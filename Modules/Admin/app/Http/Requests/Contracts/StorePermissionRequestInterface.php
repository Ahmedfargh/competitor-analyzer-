<?php

namespace Modules\Admin\Http\Requests\Contracts;

use Modules\Admin\DTOs\Contracts\PermissionDTOInterface;

interface StorePermissionRequestInterface
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array;

    public function toDTO(): PermissionDTOInterface;
}
