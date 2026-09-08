<?php

namespace Modules\Admin\DTOs\Access;

use Modules\Admin\DTOs\Contracts\RoleDTOInterface;

readonly class RoleDTO implements RoleDTOInterface
{
    /**
     * @param  array<int, string>  $permissions
     */
    public function __construct(
        public string $name,
        public string $guardName = 'admin',
        public array $permissions = []
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getGuardName(): string
    {
        return $this->guardName;
    }

    /**
     * @return array<int, string>
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'guard_name' => $this->guardName,
            'permissions' => $this->permissions,
        ];
    }
}
