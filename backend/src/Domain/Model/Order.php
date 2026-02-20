<?php

declare(strict_types=1);

namespace App\Domain\Model;

final class Order
{
    public function __construct(
        private readonly ?int $id,
        private readonly \DateTimeImmutable $createdAt,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
