<?php
declare(strict_types=1);

namespace App\Util;

final class Swagger
{
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
}
