<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Model\Order;
use App\Domain\Model\OrderItem;
use App\Domain\Repository\OrderRepositoryInterface;

final class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
    }

    /**
     * @param array{items: list<array{productId: string, quantity: int,
     *   selectedAttributes?: array<string, string>}>} $input
     * @return array{success: bool, orderId: int|null}
     */
    public function placeOrder(array $input): array
    {
        $itemsData = $input['items'] ?? [];
        if ($itemsData === [] || !is_array($itemsData)) {
            throw new \InvalidArgumentException('Items are required and must be a non-empty array.');
        }

        $orderItems = [];
        foreach ($itemsData as $item) {
            $productId = isset($item['productId']) ? trim((string) $item['productId']) : '';
            if ($productId === '') {
                throw new \InvalidArgumentException('Each item must have a productId.');
            }
            $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 0;
            if ($quantity < 1) {
                throw new \InvalidArgumentException('Quantity must be at least 1.');
            }
            $rawAttrs = isset($item['selectedAttributes']) && is_array($item['selectedAttributes'])
                ? $item['selectedAttributes']
                : [];
            $selectedAttributes = [];
            foreach ($rawAttrs as $attr) {
                if (is_array($attr) && isset($attr['id'], $attr['value'])) {
                    $selectedAttributes[(string) $attr['id']] = (string) $attr['value'];
                }
            }
            $orderItems[] = new OrderItem($productId, $quantity, $selectedAttributes);
        }

        $order = new Order(null, $orderItems, new \DateTimeImmutable());
        $orderId = $this->orderRepository->save($order);

        return ['success' => true, 'orderId' => $orderId];
    }
}
