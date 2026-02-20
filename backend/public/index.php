<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

// GraphQL endpoint: POST /graphql
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && (str_ends_with($path, '/graphql') || $path === 'graphql')) {
    header('Content-Type: application/json');
    $raw = file_get_contents('php://input') ?: '{}';
    $input = json_decode($raw, true) ?? [];
    $query = (string) ($input['query'] ?? '');
    $operationName = isset($input['operationName']) ? (string) $input['operationName'] : null;
    $variables = is_array($input['variables'] ?? null) ? $input['variables'] : [];
    $result = \App\Infrastructure\GraphQL\GraphQLHandler::handle($query, $operationName, $variables);
    echo json_encode($result);
    return;
}

use App\Domain\Model\Attribute\AttributeItem;
use App\Domain\Model\Attribute\SwatchAttribute;
use App\Domain\Model\Attribute\TextAttribute;
use App\Domain\Model\Category;
use App\Domain\Model\Currency;
use App\Domain\Model\Price;
use App\Domain\Model\Product\ConfigurableProduct;
use App\Domain\Model\Product\SimpleProduct;
use App\Domain\Factory\AttributeFactory;
use App\Domain\Factory\ProductFactory;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repository\MySQLProductRepository;

$currency = new Currency('USD', '$');
$price = new Price(19.99, $currency);
$category = new Category('tech', 'Technology');
var_dump($currency, $price, $category);

$simple = new SimpleProduct('s1', 'Simple Item', true, null, $category, null, [$price], []);
$configurable = new ConfigurableProduct('c1', 'Config Item', true, null, $category, 'Brand', [$price], [], ['color' => 'red']);
echo "\nSimpleProduct::getType() = " . $simple->getType() . "\n";
echo "ConfigurableProduct::getType() = " . $configurable->getType() . "\n";

$sizeItem1 = new AttributeItem('s-m', 'M', 'M');
$sizeItem2 = new AttributeItem('s-l', 'L', 'L');
$sizeItems = [$sizeItem1, $sizeItem2];
$textAttr = new TextAttribute('size', 'Size', $sizeItems);

$colorItem1 = new AttributeItem('c-red', 'Red', '#ff0000');
$colorItem2 = new AttributeItem('c-blue', 'Blue', '#0000ff');
$colorItems = [$colorItem1, $colorItem2];
$swatchAttr = new SwatchAttribute('color', 'Color', $colorItems);

echo "\nTextAttribute::getType() = " . $textAttr->getType() . ", items = " . count($textAttr->getItems()) . "\n";
echo "SwatchAttribute::getType() = " . $swatchAttr->getType() . ", items = " . count($swatchAttr->getItems()) . "\n";
var_dump($textAttr->getItems()[0]->getDisplayValue(), $swatchAttr->getItems()[0]->getValue());

$simpleProduct = ProductFactory::create([
    'id' => 'test-simple',
    'name' => 'Test Simple',
    'in_stock' => true,
    'category' => ['id' => 'tech', 'name' => 'Technology'],
    'prices' => [['amount' => 29.99, 'currency_label' => 'USD', 'currency_symbol' => '$']],
]);
$configurableProduct = ProductFactory::create([
    'id' => 'test-config',
    'name' => 'Test Configurable',
    'in_stock' => true,
    'attributes' => ['color' => 'red', 'size' => 'M'],
    'prices' => [],
]);
echo "\nProductFactory (no attributes): " . get_class($simpleProduct) . "\n";
echo "ProductFactory (with attributes): " . get_class($configurableProduct) . "\n";

$textAttrFromFactory = AttributeFactory::create([
    'id' => 'size',
    'name' => 'Size',
    'type' => 'text',
    'items' => [['id' => 's-m', 'display_value' => 'M', 'value' => 'M']],
]);
$swatchAttrFromFactory = AttributeFactory::create([
    'id' => 'color',
    'name' => 'Color',
    'type' => 'swatch',
    'items' => [['id' => 'c-red', 'display_value' => 'Red', 'value' => '#ff0000']],
]);
echo "AttributeFactory (type=text): " . get_class($textAttrFromFactory) . "\n";
echo "AttributeFactory (type=swatch): " . get_class($swatchAttrFromFactory) . "\n";

try {
    $connection = Connection::getInstance();
    $connection->getPdo();
    echo "\nConnected";
    $repo = new MySQLProductRepository($connection);
    $products = $repo->findAll();
    var_dump($products); // Should show array (empty for now)
} catch (PDOException $e) {
    echo "\nConnection error: " . $e->getMessage();
}
