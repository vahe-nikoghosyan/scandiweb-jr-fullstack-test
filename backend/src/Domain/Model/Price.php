<?php

declare(strict_types=1);

namespace App\Domain\Model;

final class Price
{
    public function __construct(
        private readonly float $amount,
        private readonly Currency $currency,
    ) {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
