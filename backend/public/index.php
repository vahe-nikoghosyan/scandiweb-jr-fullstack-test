<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Domain\Model\Category;
use App\Domain\Model\Currency;
use App\Domain\Model\Price;
use App\Infrastructure\Database\Connection;

$currency = new Currency('USD', '$');
$price = new Price(19.99, $currency);
$category = new Category('tech', 'Technology');
var_dump($currency, $price, $category);

try {
    Connection::getInstance()->getPdo();
    echo "\nConnected";
} catch (PDOException $e) {
    echo "\nConnection error: " . $e->getMessage();
}
