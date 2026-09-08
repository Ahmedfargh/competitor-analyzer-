<?php

namespace Modules\Admin\DTOs\Contracts;

interface PostDTOInterface
{
    public function getTitle(): string|array;

    public function getSlug(): string;

    public function getType(): string;

    public function getStatus(): string;

    public function getExcerpt(): string|array|null;

    public function getFeaturedImage(): ?string;

    public function getBlocks(): array;

    public function getSeoMeta(): array;

    public function getPublishedAt(): ?string;

    public function getAuthorId(): ?int;

    public function getTenantId(): ?string;

    public function toArray(): array;
}
