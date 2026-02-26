<?php

declare(strict_types=1);

namespace App\Domain\Model\Product;

final class SimpleProduct extends Product
{
    public function getType(): string
    {
        return 'simple';
    }
}
