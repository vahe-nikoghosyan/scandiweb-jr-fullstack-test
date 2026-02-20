<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Infrastructure\Database\Connection;

try {
    Connection::getInstance()->getPdo();
    echo 'Connected';
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
