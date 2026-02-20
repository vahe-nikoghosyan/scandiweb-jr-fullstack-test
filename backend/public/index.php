<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Domain\Model\Attribute\AttributeItem;
use App\Domain\Model\Attribute\SwatchAttribute;
use App\Domain\Model\Attribute\TextAttribute;
use App\Domain\Model\Category;
use App\Domain\Model\Currency;
use App\Domain\Model\Price;
use App\Domain\Model\Product\ConfigurableProduct;
use App\Domain\Model\Product\SimpleProduct;
use App\Infrastructure\Database\Connection;

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

try {
    Connection::getInstance()->getPdo();
    echo "\nConnected";
} catch (PDOException $e) {
    echo "\nConnection error: " . $e->getMessage();
}
