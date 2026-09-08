<?php

namespace Modules\Admin\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Admin\DTOs\Access\RoleDTO;
use Modules\Admin\DTOs\Contracts\RoleDTOInterface;
use Modules\Admin\Http\Requests\Contracts\StoreRoleRequestInterface;

class StoreRoleRequest extends FormRequest implements StoreRoleRequestInterface
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'unique:roles,name,NULL,id,guard_name,admin'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function toDTO(): RoleDTOInterface
    {
        return new RoleDTO(
            name: (string) $this->validated('name'),
            guardName: 'admin',
            permissions: (array) ($this->validated('permissions') ?? [])
        );
    }
}
