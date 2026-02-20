<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

final class OrderInputType
{
    private static ?InputObjectType $instance = null;

    public static function get(): InputObjectType
    {
        if (self::$instance === null) {
            self::$instance = new InputObjectType([
                'name' => 'OrderInput',
                'fields' => [
                    'items' => [
                        'type' => Type::nonNull(Type::listOf(Type::nonNull(OrderItemInputType::get()))),
                    ],
                ],
            ]);
        }
        return self::$instance;
    }
}
