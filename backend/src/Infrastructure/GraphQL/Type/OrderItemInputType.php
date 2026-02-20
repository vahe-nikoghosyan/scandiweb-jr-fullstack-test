<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

final class OrderItemInputType
{
    private static ?InputObjectType $instance = null;

    public static function get(): InputObjectType
    {
        if (self::$instance === null) {
            self::$instance = new InputObjectType([
                'name' => 'OrderItemInput',
                'fields' => [
                    'productId' => [
                        'type' => Type::nonNull(Type::id()),
                    ],
                    'quantity' => [
                        'type' => Type::nonNull(Type::int()),
                    ],
                    'selectedAttributes' => [
                        'type' => Type::listOf(Type::nonNull(self::attributeInputType())),
                        'description' => 'Selected attribute id -> value pairs',
                    ],
                ],
            ]);
        }
        return self::$instance;
    }

    private static function attributeInputType(): InputObjectType
    {
        static $type = null;
        if ($type === null) {
            $type = new InputObjectType([
                'name' => 'SelectedAttributeInput',
                'fields' => [
                    'id' => ['type' => Type::nonNull(Type::id())],
                    'value' => ['type' => Type::nonNull(Type::string())],
                ],
            ]);
        }
        return $type;
    }
}
