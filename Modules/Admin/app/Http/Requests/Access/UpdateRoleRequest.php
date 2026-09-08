<?php

namespace Modules\Admin\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Admin\DTOs\Access\RoleDTO;
use Modules\Admin\DTOs\Contracts\RoleDTOInterface;
use Modules\Admin\Http\Requests\Contracts\UpdateRoleRequestInterface;

class UpdateRoleRequest extends FormRequest implements UpdateRoleRequestInterface
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
        $roleId = $this->route('role') ?? $this->input('id');

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('roles', 'name')->where('guard_name', 'admin')->ignore($roleId),
            ],
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
