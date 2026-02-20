<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class CategoryType
{
    private static ?ObjectType $instance = null;

    public static function get(): ObjectType
    {
        if (self::$instance === null) {
            self::$instance = new ObjectType([
                'name' => 'Category',
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::id()),
                        'resolve' => static fn (object $category): string => $category->getId(),
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => static fn (object $category): string => $category->getName(),
                    ],
                ],
            ]);
        }
        return self::$instance;
    }
}
