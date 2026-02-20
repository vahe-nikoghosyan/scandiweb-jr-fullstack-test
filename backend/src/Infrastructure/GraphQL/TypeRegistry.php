<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL;

use App\Application\Service\CategoryService;
use App\Application\Service\ProductService;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\GraphQL\Resolver\CategoryResolver;
use App\Infrastructure\GraphQL\Resolver\ProductResolver;
use App\Infrastructure\GraphQL\Type\CategoryType;
use App\Infrastructure\GraphQL\Type\ProductType;
use App\Infrastructure\Repository\MySQLCategoryRepository;
use App\Infrastructure\Repository\MySQLProductRepository;
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
                    'resolve' => static function (): array {
                        $connection = Connection::getInstance();
                        $repository = new MySQLCategoryRepository($connection);
                        $service = new CategoryService($repository);
                        $resolver = new CategoryResolver($service);
                        return $resolver->getCategories();
                    },
                ],
                'products' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(ProductType::get()))),
                    'resolve' => static function (): array {
                        $connection = Connection::getInstance();
                        $repository = new MySQLProductRepository($connection);
                        $service = new ProductService($repository);
                        $resolver = new ProductResolver($service);
                        return $resolver->getProducts();
                    },
                ],
                'product' => [
                    'type' => ProductType::get(),
                    'args' => [
                        'id' => ['type' => Type::nonNull(Type::id())],
                    ],
                    'resolve' => static function (?object $rootValue, array $args): ?object {
                        $connection = Connection::getInstance();
                        $repository = new MySQLProductRepository($connection);
                        $service = new ProductService($repository);
                        $resolver = new ProductResolver($service);
                        return $resolver->getProductById((string) $args['id']);
                    },
                ],
            ],
        ]);
    }
}
