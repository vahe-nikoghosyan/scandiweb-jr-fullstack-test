<?php

declare(strict_types=1);

namespace App\Domain\Model;

final class OrderItem
{
    /** @param array<string, string> $selectedAttributes */
    public function __construct(
        private readonly string $productId,
        private readonly int $quantity,
        private readonly array $selectedAttributes = [],
    ) {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /** @return array<string, string> */
    public function getSelectedAttributes(): array
    {
        return $this->selectedAttributes;
    }
}
