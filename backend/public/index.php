<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$allowedOrigin = $_ENV['ALLOWED_ORIGIN'] ?? 'http://localhost:5173';
header('Access-Control-Allow-Origin: ' . $allowedOrigin);
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Credentials: true');

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
