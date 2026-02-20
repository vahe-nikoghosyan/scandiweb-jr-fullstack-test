<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

use App\Domain\Model\Product\ConfigurableProduct;
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
                            if ($product instanceof ConfigurableProduct) {
                                return []; // Attribute definitions to be wired from repo later
                            }
                            return [];
                        },
                    ],
                ],
            ]);
        }
        return self::$instance;
    }
}
