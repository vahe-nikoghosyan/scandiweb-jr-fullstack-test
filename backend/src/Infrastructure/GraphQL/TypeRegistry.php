<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL;

use App\Infrastructure\GraphQL\Type\CategoryType;
use App\Infrastructure\GraphQL\Type\ProductType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

final class TypeRegistry
{
    private static ?Schema $schema = null;

    public static function schema(): Schema
    {
        if (self::$schema === null) {
            self::$schema = new Schema([
                'query' => self::queryType(),
                'types' => [
                    \App\Infrastructure\GraphQL\Type\TextAttributeType::get(),
                    \App\Infrastructure\GraphQL\Type\SwatchAttributeType::get(),
                ],
            ]);
        }
        return self::$schema;
    }

    private static function queryType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => [
                'test' => [
                    'type' => Type::string(),
                    'resolve' => static fn (): string => 'GraphQL works',
                ],
                'categories' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(CategoryType::get()))),
                    'resolve' => static fn (): array => [],
                ],
                'products' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(ProductType::get()))),
                    'resolve' => static fn (): array => [],
                ],
            ],
        ]);
    }
}
