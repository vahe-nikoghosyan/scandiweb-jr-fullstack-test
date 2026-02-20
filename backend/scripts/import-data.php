<?php

declare(strict_types=1);

/**
 * Import data from data.json into scandiweb_store.
 * Usage: php import-data.php [path/to/data.json]
 * Default path: same directory as script (scripts/data.json).
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Infrastructure\Database\Connection;

$dataPath = $argv[1] ?? __DIR__ . '/data.json';
if (!is_readable($dataPath)) {
    fwrite(STDERR, "Cannot read: {$dataPath}\n");
    exit(1);
}

$json = file_get_contents($dataPath);
$decoded = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    fwrite(STDERR, 'Invalid JSON: ' . json_last_error_msg() . "\n");
    exit(1);
}

$data = $decoded['data'] ?? null;
if (!$data || !isset($data['categories'], $data['products'])) {
    fwrite(STDERR, "Expected structure: { data: { categories: [], products: [] } }\n");
    exit(1);
}

$pdo = Connection::getInstance()->getPdo();

// Clear in reverse FK order
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('DELETE FROM order_items');
$pdo->exec('DELETE FROM orders');
$pdo->exec('DELETE FROM product_attributes');
$pdo->exec('DELETE FROM attribute_items');
$pdo->exec('DELETE FROM gallery');
$pdo->exec('DELETE FROM prices');
$pdo->exec('DELETE FROM products');
$pdo->exec('DELETE FROM attributes');
$pdo->exec('DELETE FROM categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

// 1. Insert categories (id = name from JSON)
$catStmt = $pdo->prepare('INSERT INTO categories (id, name) VALUES (?, ?)');
foreach ($data['categories'] as $cat) {
    $name = (string) ($cat['name'] ?? '');
    if ($name === '') continue;
    $catStmt->execute([$name, $name]);
}
echo 'Categories: ' . count($data['categories']) . "\n";

// 2. Collect unique attributes from products and insert attributes + attribute_items
$attributesSeen = [];
$attrStmt = $pdo->prepare('INSERT INTO attributes (id, name, type) VALUES (?, ?, ?)');
$itemStmt = $pdo->prepare('INSERT INTO attribute_items (id, attribute_id, display_value, value) VALUES (?, ?, ?, ?)');

foreach ($data['products'] as $product) {
    $attrSets = $product['attributes'] ?? [];
    foreach ($attrSets as $attr) {
        $attrId = (string) ($attr['id'] ?? '');
        $attrName = (string) ($attr['name'] ?? $attrId);
        $attrType = (string) ($attr['type'] ?? 'text');
        if ($attrId === '') continue;
        if (isset($attributesSeen[$attrId])) continue;
        $attributesSeen[$attrId] = true;
        $attrStmt->execute([$attrId, $attrName, $attrType === 'swatch' ? 'swatch' : 'text']);
        $items = $attr['items'] ?? [];
        foreach ($items as $item) {
            $itemId = (string) ($item['id'] ?? '');
            $displayValue = (string) ($item['displayValue'] ?? $item['display_value'] ?? $itemId);
            $value = (string) ($item['value'] ?? $itemId);
            if ($itemId === '') continue;
            $uniqueItemId = $attrId . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $itemId);
            $uniqueItemId = substr($uniqueItemId, 0, 50);
            $itemStmt->execute([$uniqueItemId, $attrId, $displayValue, $value]);
        }
    }
}
echo 'Attributes: ' . count($attributesSeen) . "\n";

// 3. Insert products
$productStmt = $pdo->prepare('INSERT INTO products (id, name, in_stock, description, category_id, brand) VALUES (?, ?, ?, ?, ?, ?)');
foreach ($data['products'] as $product) {
    $id = (string) ($product['id'] ?? '');
    $name = (string) ($product['name'] ?? '');
    if ($id === '') continue;
    $inStock = (bool) ($product['inStock'] ?? true);
    $description = isset($product['description']) ? (string) $product['description'] : null;
    $categoryId = isset($product['category']) ? (string) $product['category'] : null;
    $brand = isset($product['brand']) ? (string) $product['brand'] : null;
    $productStmt->execute([$id, $name, $inStock ? 1 : 0, $description, $categoryId, $brand]);
}
echo 'Products: ' . count($data['products']) . "\n";

// 4. Insert prices
$priceStmt = $pdo->prepare('INSERT INTO prices (product_id, amount, currency_label, currency_symbol) VALUES (?, ?, ?, ?)');
foreach ($data['products'] as $product) {
    $productId = (string) ($product['id'] ?? '');
    $prices = $product['prices'] ?? [];
    foreach ($prices as $p) {
        $amount = (float) ($p['amount'] ?? 0);
        $currency = $p['currency'] ?? [];
        $label = (string) ($currency['label'] ?? 'USD');
        $symbol = (string) ($currency['symbol'] ?? '$');
        $priceStmt->execute([$productId, $amount, $label, $symbol]);
    }
}

// 5. Insert gallery
$galleryStmt = $pdo->prepare('INSERT INTO gallery (product_id, image_url) VALUES (?, ?)');
foreach ($data['products'] as $product) {
    $productId = (string) ($product['id'] ?? '');
    $gallery = $product['gallery'] ?? [];
    foreach ($gallery as $url) {
        $galleryStmt->execute([$productId, (string) $url]);
    }
}

// 6. Insert product_attributes (link product to attribute)
$paStmt = $pdo->prepare('INSERT INTO product_attributes (product_id, attribute_id) VALUES (?, ?)');
foreach ($data['products'] as $product) {
    $productId = (string) ($product['id'] ?? '');
    $attrSets = $product['attributes'] ?? [];
    foreach ($attrSets as $attr) {
        $attrId = (string) ($attr['id'] ?? '');
        if ($attrId === '') continue;
        $paStmt->execute([$productId, $attrId]);
    }
}

echo "Import done.\n";
