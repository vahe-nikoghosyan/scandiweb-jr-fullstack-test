<?php

declare(strict_types=1);

namespace App\Domain\Model;

final class Currency
{
    public function __construct(
        private readonly string $label,
        private readonly string $symbol,
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
