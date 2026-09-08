<?php

namespace Modules\Admin\DTOs\Contracts;

interface PermissionDTOInterface
{
    public function getName(): string;

    public function getGuardName(): string;

    public function getGroup(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
