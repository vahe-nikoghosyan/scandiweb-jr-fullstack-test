<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Mutation;

use App\Application\Service\OrderService;

final class PlaceOrderMutation
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {
    }

    /**
     * @param array{input: array{items: list<array{productId: string, quantity: int, selectedAttributes?: array}>}} $args
     * @return array{success: bool, orderId: int|null}
     */
    public function resolve(array $args): array
    {
        $input = $args['input'] ?? [];
        return $this->orderService->placeOrder($input);
    }
}
