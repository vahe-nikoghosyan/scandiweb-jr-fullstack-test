<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Model\Category;
use App\Domain\Model\Currency;
use App\Domain\Model\Price;
use App\Domain\Model\Product\ConfigurableProduct;
use App\Domain\Model\Product\Product;
use App\Domain\Model\Product\SimpleProduct;

final class ProductFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): Product
    {
        $id = (string) ($data['id'] ?? '');
        $name = (string) ($data['name'] ?? '');
        $inStock = (bool) ($data['in_stock'] ?? $data['inStock'] ?? true);
        $description = isset($data['description']) ? (string) $data['description'] : null;
        $category = self::buildCategory($data['category'] ?? null);
        $brand = isset($data['brand']) ? (string) $data['brand'] : null;
        $prices = self::buildPrices($data['prices'] ?? []);
        $gallery = self::buildGallery($data['gallery'] ?? []);

        $hasAttributes = isset($data['attributes'])
            && is_array($data['attributes'])
            && $data['attributes'] !== [];

        if ($hasAttributes) {
            return new ConfigurableProduct(
                $id,
                $name,
                $inStock,
                $description,
                $category,
                $brand,
                $prices,
                $gallery,
                $data['attributes'],
            );
        }

        return new SimpleProduct(
            $id,
            $name,
            $inStock,
            $description,
            $category,
            $brand,
            $prices,
            $gallery,
        );
    }

    /**
     * @param array{id: string, name: string}|null $categoryData
     */
    private static function buildCategory(mixed $categoryData): ?Category
    {
        if (!is_array($categoryData) || empty($categoryData['id'])) {
            return null;
        }
        return new Category(
            (string) $categoryData['id'],
            (string) ($categoryData['name'] ?? ''),
        );
    }

    /**
     * @param list<array{amount: float, currency_label?: string, currency_symbol?: string}> $pricesData
     * @return list<Price>
     */
    private static function buildPrices(array $pricesData): array
    {
        $prices = [];
        foreach ($pricesData as $p) {
            $amount = (float) ($p['amount'] ?? 0);
            $label = (string) ($p['currency_label'] ?? $p['currencyLabel'] ?? 'USD');
            $symbol = (string) ($p['currency_symbol'] ?? $p['currencySymbol'] ?? '$');
            $prices[] = new Price($amount, new Currency($label, $symbol));
        }
        return $prices;
    }

    /**
     * @param list<string> $galleryData
     * @return list<string>
     */
    private static function buildGallery(array $galleryData): array
    {
        $out = [];
        foreach ($galleryData as $url) {
            $out[] = (string) $url;
        }
        return $out;
    }
}
