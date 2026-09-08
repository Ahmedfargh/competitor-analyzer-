<?php

namespace Modules\Admin\DTOs\Contracts;

interface ActivityLogDTOInterface
{
    public function getAdminId(): ?int;

    public function getAction(): string;

    public function getDescription(): string;

    public function getSubjectType(): ?string;

    public function getSubjectId(): ?string;

    public function getProperties(): ?array;

    public function getIpAddress(): ?string;

    public function getUserAgent(): ?string;

    public function toArray(): array;
}
