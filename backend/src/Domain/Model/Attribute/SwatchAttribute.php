<?php

declare(strict_types=1);

namespace App\Domain\Model\Attribute;

final class SwatchAttribute extends Attribute
{
    public function getType(): string
    {
        return 'swatch';
    }
}
