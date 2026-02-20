<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Resolver;

use App\Application\Service\CategoryService;
use App\Domain\Model\Category;

final class CategoryResolver
{
    public function __construct(
        private readonly CategoryService $categoryService,
    ) {
    }

    /** @return list<Category> */
    public function getCategories(): array
    {
        return $this->categoryService->getAllCategories();
    }
}
