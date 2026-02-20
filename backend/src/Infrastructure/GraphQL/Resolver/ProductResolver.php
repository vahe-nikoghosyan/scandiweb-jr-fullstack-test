<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Resolver;

use App\Application\Service\ProductService;
use App\Domain\Model\Product\Product;

final class ProductResolver
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    /** @return list<Product> */
    public function getProducts(): array
    {
        return $this->productService->getAllProducts();
    }

    public function getProductById(string $id): ?Product
    {
        return $this->productService->getProductById($id);
    }

    /** @return list<Product> */
    public function getProductsByCategory(string $categoryId): array
    {
        return $this->productService->getProductsByCategory($categoryId);
    }
}
