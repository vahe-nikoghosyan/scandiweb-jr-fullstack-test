<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use PDO;
use PDOException;
use Dotenv\Dotenv;

final class Connection
{
    private static ?self $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $backendRoot = dirname(__DIR__, 3);
        $dotenv = Dotenv::createImmutable($backendRoot);
        $dotenv->load();

        $url = $_ENV['MYSQL_URL'] ?? $_ENV['DATABASE_URL'] ?? null;
        if ($url !== null && $url !== '') {
            $params = self::parseDatabaseUrl($url);
            if ($params !== null) {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                    $params['host'],
                    $params['port'],
                    $params['dbname']
                );
                $this->pdo = new PDO($dsn, $params['user'], $params['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
                return;
            }
        }

        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $dbname = $_ENV['DB_NAME'] ?? '';
        if ($dbname === '') {
            $dbname = $_ENV['MYSQL_DATABASE'] ?? $_ENV['MYSQLDATABASE'] ?? 'railway';
        }
        $user = $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['DB_PASSWORD'] ?? '';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $host,
            $port,
            $dbname
        );

        $this->pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    /**
     * @return array{host: string, port: string, dbname: string, user: string, password: string}|null
     */
    private static function parseDatabaseUrl(string $url): ?array
    {
        $parsed = parse_url($url);
        if ($parsed === false || !isset($parsed['host'], $parsed['user'])) {
            return null;
        }
        $host = $parsed['host'];
        $port = (string) ($parsed['port'] ?? '3306');
        $user = $parsed['user'];
        $password = $parsed['pass'] ?? '';
        $path = isset($parsed['path']) ? ltrim($parsed['path'], '/') : '';
        $dbname = $path !== '' ? $path : 'railway';

        return [
            'host' => $host,
            'port' => $port,
            'dbname' => $dbname,
            'user' => $user,
            'password' => $password,
        ];
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
