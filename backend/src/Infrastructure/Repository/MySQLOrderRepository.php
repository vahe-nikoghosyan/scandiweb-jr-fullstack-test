<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\Order;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

final class MySQLOrderRepository implements OrderRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Connection $connection)
    {
        $this->pdo = $connection->getPdo();
    }

    public function save(Order $order): int
    {
        $this->pdo->beginTransaction();
        try {
            $id = $order->getId();
            if ($id !== null) {
                // Existing order: could update or re-insert items; for now we only support insert
                $this->pdo->commit();
                return $id;
            }

            $stmt = $this->pdo->prepare('INSERT INTO orders (created_at) VALUES (?)');
            $stmt->execute([$order->getCreatedAt()->format('Y-m-d H:i:s')]);
            $orderId = (int) $this->pdo->lastInsertId();

            $itemStmt = $this->pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, selected_attributes) VALUES (?, ?, ?, ?)'
            );
            foreach ($order->getItems() as $item) {
                $json = json_encode($item->getSelectedAttributes(), JSON_THROW_ON_ERROR);
                $itemStmt->execute([
                    $orderId,
                    $item->getProductId(),
                    $item->getQuantity(),
                    $json,
                ]);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
