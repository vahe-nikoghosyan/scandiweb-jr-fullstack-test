<?php

declare(strict_types=1);

namespace App\Domain\Model;

final class Order
{
    /** @param list<OrderItem> $items */
    public function __construct(
        private readonly ?int $id,
        private readonly array $items,
        private readonly \DateTimeImmutable $createdAt,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return list<OrderItem> */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
