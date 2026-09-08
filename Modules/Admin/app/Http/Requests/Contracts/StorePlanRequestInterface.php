<?php

namespace Modules\Admin\Http\Requests\Contracts;

use Modules\Admin\DTOs\Contracts\SubscriptionPlanDTOInterface;

interface StorePlanRequestInterface
{
    public function getName(): string|array;

    public function getSlug(): string;

    public function getDescription(): string|array|null;

    public function getPriceEgp(): float;

    public function getPriceUsd(): float;

    public function toDTO(): SubscriptionPlanDTOInterface;
}
