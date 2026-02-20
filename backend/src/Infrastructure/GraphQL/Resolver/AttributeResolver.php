<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Resolver;

use App\Domain\Factory\AttributeFactory;
use App\Domain\Model\Attribute\Attribute;
use App\Domain\Model\Product\Product;
use App\Infrastructure\Database\Connection;
use PDO;

final class AttributeResolver
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    /** @return list<Attribute> */
    public function resolve(Product $product): array
    {
        $pdo = $this->connection->getPdo();
        $productId = $product->getId();

        $stmt = $pdo->prepare('SELECT attribute_id FROM product_attributes WHERE product_id = ?');
        $stmt->execute([$productId]);
        $attributeIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if ($attributeIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($attributeIds), '?'));
        $stmt = $pdo->prepare("SELECT id, name, type FROM attributes WHERE id IN ($placeholders)");
        $stmt->execute($attributeIds);
        $attrRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT id, attribute_id, display_value, value FROM attribute_items WHERE attribute_id IN ($placeholders) ORDER BY attribute_id");
        $stmt->execute($attributeIds);
        $itemRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $itemsByAttrId = [];
        foreach ($itemRows as $row) {
            $aid = (string) $row['attribute_id'];
            if (!isset($itemsByAttrId[$aid])) {
                $itemsByAttrId[$aid] = [];
            }
            $itemsByAttrId[$aid][] = [
                'id' => (string) $row['id'],
                'display_value' => (string) ($row['display_value'] ?? ''),
                'value' => (string) ($row['value'] ?? ''),
            ];
        }

        $result = [];
        foreach ($attrRows as $row) {
            $data = [
                'id' => (string) $row['id'],
                'name' => (string) ($row['name'] ?? ''),
                'type' => (string) ($row['type'] ?? 'text'),
                'items' => $itemsByAttrId[(string) $row['id']] ?? [],
            ];
            $result[] = AttributeFactory::create($data);
        }
        return $result;
    }
}
