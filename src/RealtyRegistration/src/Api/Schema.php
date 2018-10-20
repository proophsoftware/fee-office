<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use Prooph\EventMachine\JsonSchema\JsonSchema;
use Prooph\EventMachine\JsonSchema\Type\ArrayType;
use Prooph\EventMachine\JsonSchema\Type\StringType;
use Prooph\EventMachine\JsonSchema\Type\TypeRef;
use Prooph\EventMachine\JsonSchema\Type\UuidType;

class Schema
{
    public static function buildingId(): UuidType
    {
        return JsonSchema::uuid();
    }

    public static function entranceId(): UuidType
    {
        return JsonSchema::uuid();
    }

    public static function apartmentId(): UuidType
    {
        return JsonSchema::uuid();
    }

    public static function entranceAddress(): StringType
    {
        return JsonSchema::string();
    }

    public static function buildingName(): StringType
    {
        return JsonSchema::string()->withMinLength(1);
    }

    public static function buildingNameFilter(): StringType
    {
        return JsonSchema::string()->withMinLength(1);
    }

    public static function building(): TypeRef
    {
        return JsonSchema::typeRef(Aggregate::BUILDING);
    }

    public static function buildingList(): ArrayType
    {
        return JsonSchema::array(self::building());
    }

    /**
     * Common schema definitions that are useful in nearly any application.
     * Add more or remove unneeded depending on project needs.
     */
    public static function healthCheck(): TypeRef
    {
        //Health check schema is a type reference to the registered Type::HEALTH_CHECK
        return JsonSchema::typeRef(Type::HEALTH_CHECK);
    }


    /**
     * Can be used as JsonSchema::object() (optional) property definition in query payloads to enable pagination
     * @return array
     */
    public static function queryPagination(): array
    {
        return [
            Payload::SKIP => JsonSchema::nullOr(JsonSchema::integer(['minimum' => 0])),
            Payload::LIMIT => JsonSchema::nullOr(JsonSchema::integer(['minimum' => 1])),
        ];
    }

    public static function iso8601DateTime(): StringType
    {
        return JsonSchema::string()->withPattern('^\d{4}-\d\d-\d\dT\d\d:\d\d:\d\d(\.\d+)?(([+-]\d\d:\d\d)|Z)?$');
    }
    
    public static function iso8601Date(): StringType
    {
        return JsonSchema::string()->withPattern('^\d{4}-\d\d-\d\d$');
    }
}
