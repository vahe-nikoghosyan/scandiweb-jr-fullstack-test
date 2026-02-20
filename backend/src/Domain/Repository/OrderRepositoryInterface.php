<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Order;

interface OrderRepositoryInterface
{
    /** Returns the saved order ID (new or existing). */
    public function save(Order $order): int;
}
