<?php

namespace Modules\Admin\DTOs\Access;

use Modules\Admin\DTOs\Contracts\PermissionDTOInterface;

readonly class PermissionDTO implements PermissionDTOInterface
{
    public function __construct(
        public string $name,
        public string $guardName = 'admin',
        public ?string $group = null
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getGuardName(): string
    {
        return $this->guardName;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'guard_name' => $this->guardName,
            'group' => $this->group,
        ];
    }
}
