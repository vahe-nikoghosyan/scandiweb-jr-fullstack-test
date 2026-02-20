<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class CurrencyType
{
    private static ?ObjectType $instance = null;

    public static function get(): ObjectType
    {
        if (self::$instance === null) {
            self::$instance = new ObjectType([
                'name' => 'Currency',
                'fields' => [
                    'label' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => static fn (object $currency): string => $currency->getLabel(),
                    ],
                    'symbol' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => static fn (object $currency): string => $currency->getSymbol(),
                    ],
                ],
            ]);
        }
        return self::$instance;
    }
}
