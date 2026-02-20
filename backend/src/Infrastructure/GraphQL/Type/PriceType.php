<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class PriceType
{
    private static ?ObjectType $instance = null;

    public static function get(): ObjectType
    {
        if (self::$instance === null) {
            self::$instance = new ObjectType([
                'name' => 'Price',
                'fields' => [
                    'amount' => [
                        'type' => Type::nonNull(Type::float()),
                        'resolve' => static fn (object $price): float => $price->getAmount(),
                    ],
                    'currency' => [
                        'type' => Type::nonNull(CurrencyType::get()),
                        'resolve' => static fn (object $price): object => $price->getCurrency(),
                    ],
                ],
            ]);
        }
        return self::$instance;
    }
}
