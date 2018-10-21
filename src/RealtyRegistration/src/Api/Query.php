<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use FeeOffice\RealtyRegistration\Infrastructure\System\HealthCheckResolver;
use FeeOffice\RealtyRegistration\Infrastructure\Resolver;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\JsonSchema\JsonSchema;

class Query implements EventMachineDescription
{
    /**
     * Define query names using constants
     *
     * For a clean and simple API it is recommended to just use the name of the "thing"
     * you want to return as query name, see example for user queries:
     *
     * @example
     *
     * const USER = 'User';
     * const USERS = 'Users';
     * const FRIENDS = 'Friends';
     */

    /**
     * Default Query, used to perform health checks using the messagebox endpoint
     */
    const HEALTH_CHECK = 'HealthCheck';

    const BUILDING = 'Building';
    const BUILDINGS = 'Buildings';

    public static function describe(EventMachine $eventMachine): void
    {
        $eventMachine->registerQuery(self::BUILDINGS)
            ->resolveWith(Resolver\Buildings::class)
            ->setReturnType(Schema::buildingList());

        $eventMachine->registerQuery(self::BUILDING, JsonSchema::object([], [
            Payload::BUILDING_ID => JsonSchema::nullOr(Schema::buildingId()),
            Payload::ENTRANCE_ID => JsonSchema::nullOr(Schema::entranceId()),
            Payload::APARTMENT_ID => JsonSchema::nullOr(Schema::apartmentId())
        ]))
            ->resolveWith(Resolver\Building::class)
            ->setReturnType(Schema::building());

        //Default query: can be used to check if service is up and running
        $eventMachine->registerQuery(self::HEALTH_CHECK) //<-- Payload schema is optional for queries
            ->resolveWith(HealthCheckResolver::class) //<-- Service id (usually FQCN) to get resolver from DI container
            ->setReturnType(Schema::healthCheck()); //<-- Type returned by resolver
    }
}
