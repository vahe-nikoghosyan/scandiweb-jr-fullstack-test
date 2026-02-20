<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class TextAttributeType
{
    private static ?ObjectType $instance = null;

    public static function get(): ObjectType
    {
        if (self::$instance === null) {
            self::$instance = new ObjectType([
                'name' => 'TextAttribute',
                'interfaces' => [AttributeType::get()],
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::id()),
                        'resolve' => static fn (object $attr): string => $attr->getId(),
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => static fn (object $attr): string => $attr->getName(),
                    ],
                    'items' => [
                        'type' => Type::nonNull(Type::listOf(Type::nonNull(AttributeItemType::get()))),
                        'resolve' => static fn (object $attr): array => $attr->getItems(),
                    ],
                    'type' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => static fn (object $attr): string => $attr->getType(),
                    ],
                ],
            ]);
        }
        return self::$instance;
    }
}
