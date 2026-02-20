<?php

declare(strict_types=1);

namespace App\Domain\Model\Attribute;

abstract class Attribute
{
    /** @param list<AttributeItem> $items */
    public function __construct(
        protected readonly string $id,
        protected readonly string $name,
        protected readonly array $items,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @return list<AttributeItem> */
    public function getItems(): array
    {
        return $this->items;
    }

    abstract public function getType(): string;
}
