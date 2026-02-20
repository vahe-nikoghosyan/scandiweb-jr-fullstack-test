<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Model\Attribute\Attribute;
use App\Domain\Model\Attribute\AttributeItem;
use App\Domain\Model\Attribute\SwatchAttribute;
use App\Domain\Model\Attribute\TextAttribute;

final class AttributeFactory
{
    /**
     * @param array{id: string, name: string, type: string, items?: list<array{id: string, display_value?: string, value: string}>} $data
     */
    public static function create(array $data): Attribute
    {
        $id = (string) ($data['id'] ?? '');
        $name = (string) ($data['name'] ?? '');
        $items = self::buildItems($data['items'] ?? []);
        $type = strtolower((string) ($data['type'] ?? 'text'));

        return match ($type) {
            'swatch' => new SwatchAttribute($id, $name, $items),
            default => new TextAttribute($id, $name, $items),
        };
    }

    /**
     * @param list<array{id: string, display_value?: string, value?: string}> $itemsData
     * @return list<AttributeItem>
     */
    private static function buildItems(array $itemsData): array
    {
        $items = [];
        foreach ($itemsData as $item) {
            $itemId = (string) ($item['id'] ?? '');
            $displayValue = (string) ($item['display_value'] ?? $item['displayValue'] ?? '');
            $value = (string) ($item['value'] ?? '');
            $items[] = new AttributeItem($itemId, $displayValue, $value);
        }
        return $items;
    }
}
