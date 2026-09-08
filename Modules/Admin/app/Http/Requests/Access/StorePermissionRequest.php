<?php

namespace Modules\Admin\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Admin\DTOs\Access\PermissionDTO;
use Modules\Admin\DTOs\Contracts\PermissionDTOInterface;
use Modules\Admin\Http\Requests\Contracts\StorePermissionRequestInterface;

class StorePermissionRequest extends FormRequest implements StorePermissionRequestInterface
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
            'name' => ['required', 'string', 'max:50', 'unique:permissions,name,NULL,id,guard_name,admin'],
            'group' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function toDTO(): PermissionDTOInterface
    {
        return new PermissionDTO(
            name: (string) $this->validated('name'),
            guardName: 'admin',
            group: $this->filled('group') ? (string) $this->validated('group') : null
        );
    }
}
