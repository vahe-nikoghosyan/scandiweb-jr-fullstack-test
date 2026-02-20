<?php

declare(strict_types=1);

namespace App\Domain\Model\Product;

use App\Domain\Model\Category;
use App\Domain\Model\Price;

abstract class Product
{
    public function __construct(
        protected readonly string $id,
        protected readonly string $name,
        protected readonly bool $inStock,
        protected readonly ?string $description,
        protected readonly ?Category $category,
        protected readonly ?string $brand,
        /** @var list<Price> */
        protected readonly array $prices,
        /** @var list<string> */
        protected readonly array $gallery,
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

    public function isInStock(): bool
    {
        return $this->inStock;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /** @return list<Price> */
    public function getPrices(): array
    {
        return $this->prices;
    }

    /** @return list<string> */
    public function getGallery(): array
    {
        return $this->gallery;
    }

    abstract public function getType(): string;
}
