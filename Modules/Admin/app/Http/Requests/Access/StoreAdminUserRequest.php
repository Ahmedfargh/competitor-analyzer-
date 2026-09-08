<?php

namespace Modules\Admin\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\Admin\DTOs\Access\AdminUserDTO;
use Modules\Admin\DTOs\Contracts\AdminUserDTOInterface;
use Modules\Admin\Http\Requests\Contracts\StoreAdminUserRequestInterface;

class StoreAdminUserRequest extends FormRequest implements StoreAdminUserRequestInterface
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    public function toDTO(): AdminUserDTOInterface
    {
        return new AdminUserDTO(
            name: (string) $this->validated('name'),
            email: (string) $this->validated('email'),
            password: (string) $this->validated('password'),
            roles: (array) ($this->validated('roles') ?? [])
        );
    }
}
