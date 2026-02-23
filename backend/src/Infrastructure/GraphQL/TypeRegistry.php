<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL;

use GraphQL\Error\Error;
use App\Application\Service\CategoryService;
use App\Application\Service\OrderService;
use App\Application\Service\ProductService;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\GraphQL\Mutation\PlaceOrderMutation;
use App\Infrastructure\GraphQL\Resolver\CategoryResolver;
use App\Infrastructure\GraphQL\Resolver\ProductResolver;
use App\Infrastructure\GraphQL\Type\CategoryType;
use App\Infrastructure\GraphQL\Type\OrderInputType;
use App\Infrastructure\GraphQL\Type\OrderResultType;
use App\Infrastructure\GraphQL\Type\ProductType;
use App\Infrastructure\Repository\MySQLCategoryRepository;
use App\Infrastructure\Repository\MySQLOrderRepository;
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
                'mutation' => self::mutationType(),
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
                        try {
                            $connection = Connection::getInstance();
                            $repository = new MySQLCategoryRepository($connection);
                            $service = new CategoryService($repository);
                            $resolver = new CategoryResolver($service);
                            return $resolver->getCategories();
                        } catch (\Throwable $e) {
                            throw new Error(
                                'Failed to load categories.',
                                null,
                                null,
                                null,
                                null,
                                $e,
                                ['code' => 'CATEGORIES_ERROR', 'details' => $e->getMessage()]
                            );
                        }
                    },
                ],
                'products' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(ProductType::get()))),
                    'resolve' => static function (): array {
                        try {
                            $connection = Connection::getInstance();
                            $repository = new MySQLProductRepository($connection);
                            $service = new ProductService($repository);
                            $resolver = new ProductResolver($service);
                            return $resolver->getProducts();
                        } catch (\Throwable $e) {
                            throw new Error(
                                'Failed to load products.',
                                null,
                                null,
                                null,
                                null,
                                $e,
                                ['code' => 'PRODUCTS_ERROR', 'details' => $e->getMessage()]
                            );
                        }
                    },
                ],
                'product' => [
                    'type' => ProductType::get(),
                    'args' => [
                        'id' => ['type' => Type::nonNull(Type::id())],
                    ],
                    'resolve' => static function (?object $rootValue, array $args): ?object {
                        try {
                            $connection = Connection::getInstance();
                            $repository = new MySQLProductRepository($connection);
                            $service = new ProductService($repository);
                            $resolver = new ProductResolver($service);
                            return $resolver->getProductById((string) $args['id']);
                        } catch (\Throwable $e) {
                            throw new Error(
                                'Failed to load product.',
                                null,
                                null,
                                null,
                                null,
                                $e,
                                ['code' => 'PRODUCT_ERROR', 'details' => $e->getMessage()]
                            );
                        }
                    },
                ],
            ],
        ]);
    }

    private static function mutationType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'placeOrder' => [
                    'type' => Type::nonNull(OrderResultType::get()),
                    'args' => [
                        'input' => ['type' => Type::nonNull(OrderInputType::get())],
                    ],
                    'resolve' => static function (?object $rootValue, array $args): array {
                        try {
                            $connection = Connection::getInstance();
                            $repository = new MySQLOrderRepository($connection);
                            $service = new OrderService($repository);
                            $mutation = new PlaceOrderMutation($service);
                            return $mutation->resolve($args);
                        } catch (\Throwable $e) {
                            throw new Error(
                                'Failed to place order.',
                                null,
                                null,
                                null,
                                null,
                                $e,
                                ['code' => 'PLACE_ORDER_ERROR', 'details' => $e->getMessage()]
                            );
                        }
                    },
                ],
            ],
        ]);
    }
}
