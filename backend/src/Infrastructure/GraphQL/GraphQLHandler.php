<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL;

use GraphQL\GraphQL;

final class GraphQLHandler
{
    public static function handle(string $query, ?string $operationName = null, array $variables = []): array
    {
        $schema = TypeRegistry::schema();
        $result = GraphQL::executeQuery($schema, $query, null, null, $variables, $operationName);
        return $result->toArray();
    }
}
