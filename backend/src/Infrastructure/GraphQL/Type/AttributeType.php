<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;

final class AttributeType
{
    private static ?InterfaceType $instance = null;

    public static function get(): InterfaceType
    {
        if (self::$instance === null) {
            self::$instance = new InterfaceType([
                'name' => 'Attribute',
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::id()),
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                    ],
                    'items' => [
                        'type' => Type::nonNull(Type::listOf(Type::nonNull(AttributeItemType::get()))),
                    ],
                    'type' => [
                        'type' => Type::nonNull(Type::string()),
                    ],
                ],
                'resolveType' => static function (object $value): \GraphQL\Type\Definition\ObjectType {
                    $type = $value->getType();
                    return $type === 'swatch' ? SwatchAttributeType::get() : TextAttributeType::get();
                },
            ]);
        }
        return self::$instance;
    }
}
