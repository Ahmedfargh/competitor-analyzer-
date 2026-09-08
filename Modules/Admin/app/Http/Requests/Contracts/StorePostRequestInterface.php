<?php

namespace Modules\Admin\Http\Requests\Contracts;

use Modules\Admin\DTOs\Contracts\PostDTOInterface;

interface StorePostRequestInterface
{
    public function authorize(): bool;

    public function rules(): array;

    public function toDTO(): PostDTOInterface;
}
