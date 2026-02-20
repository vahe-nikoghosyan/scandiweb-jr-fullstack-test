<?php

declare(strict_types=1);

namespace App\Domain\Model\Product;

use App\Domain\Model\Category;
use App\Domain\Model\Price;

final class SimpleProduct extends Product
{
    public function getType(): string
    {
        return 'simple';
    }
}
