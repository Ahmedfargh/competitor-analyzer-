<?php

namespace Modules\Admin\Http\Requests\Contracts;

interface AdminLoginRequestInterface
{
    public function getEmail(): string;

    public function getPassword(): string;

    public function isRemember(): bool;

    public function getCredentials(): array;
}
