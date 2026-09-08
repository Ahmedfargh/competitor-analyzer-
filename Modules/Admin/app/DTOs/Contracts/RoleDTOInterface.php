<?php

namespace Modules\Admin\DTOs\Contracts;

interface RoleDTOInterface
{
    public function getName(): string;

    public function getGuardName(): string;

    /**
     * @return array<int, string>
     */
    public function getPermissions(): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
