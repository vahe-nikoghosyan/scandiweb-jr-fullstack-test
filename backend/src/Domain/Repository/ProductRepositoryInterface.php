<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Product\Product;

interface ProductRepositoryInterface
{
    /** @return list<Product> */
    public function findAll(): array;

    public function findById(string $id): ?Product;

    /** @return list<Product> */
    public function findByCategory(string $categoryId): array;
}
