<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Model\Product\Product;
use App\Domain\Repository\ProductRepositoryInterface;

final class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
    ) {
    }

    /** @return list<Product> */
    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    public function getProductById(string $id): ?Product
    {
        return $this->productRepository->findById($id);
    }

    /** @return list<Product> */
    public function getProductsByCategory(string $categoryId): array
    {
        return $this->productRepository->findByCategory($categoryId);
    }
}
