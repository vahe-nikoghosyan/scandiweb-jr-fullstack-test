<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class AttributeItemType
{
    private static ?ObjectType $instance = null;

    public static function get(): ObjectType
    {
        if (self::$instance === null) {
            self::$instance = new ObjectType([
                'name' => 'AttributeItem',
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::id()),
                        'resolve' => static fn (object $item): string => $item->getId(),
                    ],
                    'displayValue' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => static fn (object $item): string => $item->getDisplayValue(),
                    ],
                    'value' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => static fn (object $item): string => $item->getValue(),
                    ],
                ],
            ]);
        }
        return self::$instance;
    }
}
