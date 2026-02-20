<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class OrderResultType
{
    private static ?ObjectType $instance = null;

    public static function get(): ObjectType
    {
        if (self::$instance === null) {
            self::$instance = new ObjectType([
                'name' => 'OrderResult',
                'fields' => [
                    'success' => [
                        'type' => Type::nonNull(Type::boolean()),
                        'resolve' => static fn (array $result): bool => $result['success'],
                    ],
                    'orderId' => [
                        'type' => Type::id(),
                        'resolve' => static function (array $result): ?string {
                            $id = $result['orderId'] ?? null;
                            return $id !== null ? (string) $id : null;
                        },
                    ],
                ],
            ]);
        }
        return self::$instance;
    }
}
