<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Factory\ProductFactory;
use App\Domain\Model\Product\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

final class MySQLProductRepository implements ProductRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Connection $connection)
    {
        $this->pdo = $connection->getPdo();
    }

    /** @return list<Product> */
    public function findAll(): array
    {
        $stmt = $this->pdo->query('
            SELECT p.id, p.name, p.in_stock, p.description, p.category_id, p.brand,
                   c.id AS category_id_ref, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.name
        ');
        $productRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $productIds = array_column($productRows, 'id');
        $pricesByProduct = $this->fetchPricesByProductIds($productIds);
        $galleryByProduct = $this->fetchGalleryByProductIds($productIds);
        $attributesByProduct = $this->fetchAttributeIdsByProductIds($productIds);

        $result = [];
        foreach ($productRows as $row) {
            $id = (string) $row['id'];
            $prices = $pricesByProduct[$id] ?? [];
            $gallery = $galleryByProduct[$id] ?? [];
            $attributeIds = $attributesByProduct[$id] ?? [];
            $attributes = [];
            foreach ($attributeIds as $attrId) {
                $attributes[$attrId] = '';
            }

            $data = [
                'id' => $id,
                'name' => (string) $row['name'],
                'in_stock' => (bool) $row['in_stock'],
                'description' => $row['description'] !== null ? (string) $row['description'] : null,
                'category' => $row['category_id_ref'] !== null
                    ? ['id' => (string) $row['category_id_ref'], 'name' => (string) $row['category_name']]
                    : null,
                'brand' => $row['brand'] !== null ? (string) $row['brand'] : null,
                'prices' => $prices,
                'gallery' => $gallery,
                'attributes' => $attributes,
            ];
            $result[] = ProductFactory::create($data);
        }
        return $result;
    }

    public function findById(string $id): ?Product
    {
        $stmt = $this->pdo->prepare('
            SELECT p.id, p.name, p.in_stock, p.description, p.category_id, p.brand,
                   c.id AS category_id_ref, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = ?
        ');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        $prices = $this->fetchPricesByProductIds([$id])[$id] ?? [];
        $gallery = $this->fetchGalleryByProductIds([$id])[$id] ?? [];
        $attributeIds = $this->fetchAttributeIdsByProductIds([$id])[$id] ?? [];
        $attributes = [];
        foreach ($attributeIds as $attrId) {
            $attributes[$attrId] = '';
        }

        $data = [
            'id' => (string) $row['id'],
            'name' => (string) $row['name'],
            'in_stock' => (bool) $row['in_stock'],
            'description' => $row['description'] !== null ? (string) $row['description'] : null,
            'category' => $row['category_id_ref'] !== null
                ? ['id' => (string) $row['category_id_ref'], 'name' => (string) $row['category_name']]
                : null,
            'brand' => $row['brand'] !== null ? (string) $row['brand'] : null,
            'prices' => $prices,
            'gallery' => $gallery,
            'attributes' => $attributes,
        ];
        return ProductFactory::create($data);
    }

    /** @return list<Product> */
    public function findByCategory(string $categoryId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT p.id, p.name, p.in_stock, p.description, p.category_id, p.brand,
                   c.id AS category_id_ref, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.category_id = ?
            ORDER BY p.name
        ');
        $stmt->execute([$categoryId]);
        $productRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $productIds = array_column($productRows, 'id');
        $pricesByProduct = $productIds !== [] ? $this->fetchPricesByProductIds($productIds) : [];
        $galleryByProduct = $productIds !== [] ? $this->fetchGalleryByProductIds($productIds) : [];
        $attributesByProduct = $productIds !== [] ? $this->fetchAttributeIdsByProductIds($productIds) : [];

        $result = [];
        foreach ($productRows as $row) {
            $id = (string) $row['id'];
            $prices = $pricesByProduct[$id] ?? [];
            $gallery = $galleryByProduct[$id] ?? [];
            $attributeIds = $attributesByProduct[$id] ?? [];
            $attributes = [];
            foreach ($attributeIds as $attrId) {
                $attributes[$attrId] = '';
            }
            $data = [
                'id' => $id,
                'name' => (string) $row['name'],
                'in_stock' => (bool) $row['in_stock'],
                'description' => $row['description'] !== null ? (string) $row['description'] : null,
                'category' => $row['category_id_ref'] !== null
                    ? ['id' => (string) $row['category_id_ref'], 'name' => (string) $row['category_name']]
                    : null,
                'brand' => $row['brand'] !== null ? (string) $row['brand'] : null,
                'prices' => $prices,
                'gallery' => $gallery,
                'attributes' => $attributes,
            ];
            $result[] = ProductFactory::create($data);
        }
        return $result;
    }

    /**
     * @param list<string> $productIds
     * @return array<string, list<array{amount: float, currency_label: string, currency_symbol: string}>>
     */
    private function fetchPricesByProductIds(array $productIds): array
    {
        if ($productIds === []) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = $this->pdo->prepare("SELECT product_id, amount, currency_label, currency_symbol FROM prices WHERE product_id IN ($placeholders)");
        $stmt->execute($productIds);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $byProduct = [];
        foreach ($productIds as $id) {
            $byProduct[$id] = [];
        }
        foreach ($rows as $row) {
            $pid = (string) $row['product_id'];
            $byProduct[$pid][] = [
                'amount' => (float) $row['amount'],
                'currency_label' => (string) ($row['currency_label'] ?? 'USD'),
                'currency_symbol' => (string) ($row['currency_symbol'] ?? '$'),
            ];
        }
        return $byProduct;
    }

    /**
     * @param list<string> $productIds
     * @return array<string, list<string>>
     */
    private function fetchGalleryByProductIds(array $productIds): array
    {
        if ($productIds === []) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = $this->pdo->prepare("SELECT product_id, image_url FROM gallery WHERE product_id IN ($placeholders)");
        $stmt->execute($productIds);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $byProduct = [];
        foreach ($productIds as $id) {
            $byProduct[$id] = [];
        }
        foreach ($rows as $row) {
            $pid = (string) $row['product_id'];
            $byProduct[$pid][] = (string) $row['image_url'];
        }
        return $byProduct;
    }

    /**
     * @param list<string> $productIds
     * @return array<string, list<string>>
     */
    private function fetchAttributeIdsByProductIds(array $productIds): array
    {
        if ($productIds === []) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = $this->pdo->prepare("SELECT product_id, attribute_id FROM product_attributes WHERE product_id IN ($placeholders)");
        $stmt->execute($productIds);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $byProduct = [];
        foreach ($productIds as $id) {
            $byProduct[$id] = [];
        }
        foreach ($rows as $row) {
            $pid = (string) $row['product_id'];
            $byProduct[$pid][] = (string) $row['attribute_id'];
        }
        return $byProduct;
    }
}
