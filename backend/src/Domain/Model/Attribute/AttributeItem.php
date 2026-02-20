<?php

declare(strict_types=1);

namespace App\Domain\Model\Attribute;

final class AttributeItem
{
    public function __construct(
        private readonly string $id,
        private readonly string $displayValue,
        private readonly string $value,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDisplayValue(): string
    {
        return $this->displayValue;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
