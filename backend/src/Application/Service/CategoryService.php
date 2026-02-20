<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Model\Category;
use App\Domain\Repository\CategoryRepositoryInterface;

final class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    /** @return list<Category> */
    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAll();
    }
}
