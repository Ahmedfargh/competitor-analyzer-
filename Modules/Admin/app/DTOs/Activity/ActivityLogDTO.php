<?php

namespace Modules\Admin\DTOs\Activity;

use Modules\Admin\DTOs\Contracts\ActivityLogDTOInterface;

class ActivityLogDTO implements ActivityLogDTOInterface
{
    /**
     * @param  array<string, mixed>|null  $properties
     */
    public function __construct(
        public readonly string $action,
        public readonly string $description,
        public readonly ?int $adminId = null,
        public readonly ?string $subjectType = null,
        public readonly ?string $subjectId = null,
        public readonly ?array $properties = null,
        public readonly ?string $ipAddress = null,
        public readonly ?string $userAgent = null
    ) {}

    public function getAdminId(): ?int
    {
        return $this->adminId;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSubjectType(): ?string
    {
        return $this->subjectType;
    }

    public function getSubjectId(): ?string
    {
        return $this->subjectId;
    }

    public function getProperties(): ?array
    {
        return $this->properties;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function toArray(): array
    {
        return [
            'admin_id' => $this->adminId,
            'action' => $this->action,
            'description' => $this->description,
            'subject_type' => $this->subjectType,
            'subject_id' => $this->subjectId,
            'properties' => $this->properties,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
        ];
    }
}
