<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

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

try {
    Connection::getInstance()->getPdo();
    echo "\nConnected";
} catch (PDOException $e) {
    echo "\nConnection error: " . $e->getMessage();
}
