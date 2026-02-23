<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use App\Infrastructure\Database\Connection;
use App\Infrastructure\GraphQL\Resolver\AttributeResolver;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class ProductType
{
    private static ?ObjectType $instance = null;

    public static function get(): ObjectType
    {
        if (self::$instance === null) {
            self::$instance = new ObjectType([
                'name' => 'Product',
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::id()),
                        'resolve' => static fn (object $product): string => $product->getId(),
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => static fn (object $product): string => $product->getName(),
                    ],
                    'inStock' => [
                        'type' => Type::nonNull(Type::boolean()),
                        'resolve' => static fn (object $product): bool => $product->isInStock(),
                    ],
                    'description' => [
                        'type' => Type::string(),
                        'resolve' => static fn (object $product): ?string => $product->getDescription(),
                    ],
                    'category' => [
                        'type' => CategoryType::get(),
                        'resolve' => static fn (object $product): ?object => $product->getCategory(),
                    ],
                    'brand' => [
                        'type' => Type::string(),
                        'resolve' => static fn (object $product): ?string => $product->getBrand(),
                    ],
                    'prices' => [
                        'type' => Type::nonNull(Type::listOf(Type::nonNull(PriceType::get()))),
                        'resolve' => static fn (object $product): array => $product->getPrices(),
                    ],
                    'gallery' => [
                        'type' => Type::nonNull(Type::listOf(Type::nonNull(Type::string()))),
                        'resolve' => static fn (object $product): array => $product->getGallery(),
                    ],
                    'attributes' => [
                        'type' => Type::listOf(Type::nonNull(AttributeType::get())),
                        'resolve' => static function (object $product): array {
                            try {
                                $resolver = new AttributeResolver(Connection::getInstance());
                                return $resolver->resolve($product);
                            } catch (\Throwable $e) {
                                throw new Error(
                                    'Failed to load product attributes.',
                                    null,
                                    null,
                                    null,
                                    null,
                                    $e,
                                    ['code' => 'ATTRIBUTES_ERROR', 'details' => $e->getMessage()]
                                );
                            }
                        },
                    ],
                ],
            ]);
        }
        return self::$instance;
    }
}
