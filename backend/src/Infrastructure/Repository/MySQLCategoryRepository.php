<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\Category;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

final class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Connection $connection)
    {
        $this->pdo = $connection->getPdo();
    }

    /** @return list<Category> */
    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT id, name FROM categories ORDER BY name');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows as $row) {
            $result[] = new Category((string) $row['id'], (string) $row['name']);
        }
        return $result;
    }

    public function findById(string $id): ?Category
    {
        $stmt = $this->pdo->prepare('SELECT id, name FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        return new Category((string) $row['id'], (string) $row['name']);
    }
}
