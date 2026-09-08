<?php

namespace Modules\Admin\DTOs\Contracts;

interface AdminUserDTOInterface
{
    public function getName(): string;

    public function getEmail(): string;

    public function getPassword(): ?string;

    /**
     * @return array<int, string>
     */
    public function getRoles(): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
