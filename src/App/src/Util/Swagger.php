<?php
declare(strict_types=1);

namespace App\Util;

use App\Util\Swagger\Operation;

final class Swagger
{
    public const TAGS = 'tags';
    public const SUMMARY = 'summary';
    public const DESCRIPTION = 'description';
    public const OPERATION_ID = 'operationId';
    public const REQUEST_BODY = 'requestBody';
    public const PARAMETERS = 'parameters';
    public const PARAM_NAME = 'name';
    public const PARAM_IN = 'in';
    public const PATH = 'path';
    public const QUERY = 'query';
    public const HEADER = 'header';
    public const REQUIRED = 'required';
    public const SCHEMA = 'schema';
    public const RESPONSES = 'responses';
    public const CONTENT = 'content';
    public const APPLICATION_JSON = 'application/json';
    public const HEADERS = 'headers';
    public const EXTERNAL_DOCS = 'externalDocs';


    public static function jsonSchemaToOpenApiSchema(array $jsonSchema): array
    {
        if(isset($jsonSchema['type']) && is_array($jsonSchema['type'])) {
            $type = null;
            $containsNull = false;
            foreach ($jsonSchema['type'] as $possibleType) {
                if(mb_strtolower($possibleType) !== 'null') {
                    if($type) {
                        throw new \RuntimeException("Got JSON Schema type defined as an array with more than one type + NULL set. " . json_encode($jsonSchema));
                    }
                    $type = $possibleType;
                } else {
                    $containsNull = true;
                }
            }
            $jsonSchema['type'] = $type;
            if($containsNull) {
                $jsonSchema['nullable'] = true;
            }
        }
        if(isset($jsonSchema['properties']) && is_array($jsonSchema['properties'])) {
            foreach ($jsonSchema['properties'] as $propName => $propSchema) {
                $jsonSchema['properties'][$propName] = self::jsonSchemaToOpenApiSchema($propSchema);
            }
        }
        if(isset($jsonSchema['items']) && is_array($jsonSchema['items'])) {
            $jsonSchema['items'] = self::jsonSchemaToOpenApiSchema($jsonSchema['items']);
        }
        if(isset($jsonSchema['$ref'])) {
            $jsonSchema['$ref'] = str_replace('definitions', 'components/schemas', $jsonSchema['$ref']);
        }
        return $jsonSchema;
    }

    /**
     * @param string $fastRoute
     * @param string $method
     * @return Operation[]
     */
    public static function fastRouteToOperations(string $fastRoute, string $method): array
    {
        return Operation::fromFastRouteAndMethod($fastRoute, $method);
    }
}
