<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Category;

interface CategoryRepositoryInterface
{
    /** @return list<Category> */
    public function findAll(): array;

    public function findById(string $id): ?Category;
}
