<?php

namespace Modules\Admin\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Admin\DTOs\Access\AdminUserDTO;
use Modules\Admin\DTOs\Contracts\AdminUserDTOInterface;
use Modules\Admin\Http\Requests\Contracts\UpdateAdminUserRequestInterface;

class UpdateAdminUserRequest extends FormRequest implements UpdateAdminUserRequestInterface
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
        $adminId = $this->route('admin') ?? $this->route('user') ?? $this->input('id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($adminId),
            ],
            'password' => ['nullable', 'string', Password::defaults(), 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    public function toDTO(): AdminUserDTOInterface
    {
        return new AdminUserDTO(
            name: (string) $this->validated('name'),
            email: (string) $this->validated('email'),
            password: $this->filled('password') ? (string) $this->validated('password') : null,
            roles: (array) ($this->validated('roles') ?? [])
        );
    }
}
