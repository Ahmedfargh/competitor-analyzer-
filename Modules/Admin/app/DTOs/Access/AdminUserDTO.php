<?php

namespace Modules\Admin\DTOs\Access;

use Modules\Admin\DTOs\Contracts\AdminUserDTOInterface;

readonly class AdminUserDTO implements AdminUserDTOInterface
{
    /**
     * @param  array<int, string>  $roles
     */
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public array $roles = []
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles,
        ];

        if ($this->password !== null) {
            $data['password'] = $this->password;
        }

        return $data;
    }
}
