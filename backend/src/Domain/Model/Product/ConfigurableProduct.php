<?php

declare(strict_types=1);

namespace App\Domain\Model\Product;

use App\Domain\Model\Category;

final class ConfigurableProduct extends Product
{
    /** @param array<string, mixed> $attributes */
    public function __construct(
        string $id,
        string $name,
        bool $inStock,
        ?string $description,
        ?Category $category,
        ?string $brand,
        array $prices,
        array $gallery,
        private readonly array $attributes = [],
    ) {
        parent::__construct($id, $name, $inStock, $description, $category, $brand, $prices, $gallery);
    }

    /** @return array<string, mixed> */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getType(): string
    {
        return 'configurable';
    }
}
