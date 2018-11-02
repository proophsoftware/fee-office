<?php

declare(strict_types=1);

namespace FeeOffice\ContractManagement\Api;

use FeeOffice\ContractManagement\Model\Contract\ContractPeriod;
use FeeOffice\RealtyRegistration\Model\Apartment;
use FeeOffice\RealtyRegistration\Model\Building;
use FeeOffice\RealtyRegistration\Model\Entrance;
use FeeOffice\RealtyRegistration\Infrastructure\Resolver;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\JsonSchema\JsonSchema;
use Prooph\EventMachine\JsonSchema\Type\ObjectType;

class Type implements EventMachineDescription
{
    /**
     * Define constants for query return types. Do not mix up return types with App\Api\Aggregate types.
     * Both can have the same name and probably represent the same data but you can and should keep them separated.
     * Aggregate types are for your write model and query return types are for your read model.
     *
     * @example
     *
     * const USER = 'User';
     *
     * You can use private static methods to define the type schemas and then register them in event machine together with the type name
     * private static function user(): array
     * {
     *      return JsonSchema::object([
     *          Payload::USER_ID => Schema::userId(),
     *          Payload::USERNAME => Schema::username()
     *      ])
     * }
     *
     * Queries should only use type references as return types (at least when return type is an object).
     * @see \App\Api\Query for more about query return types
     */


    const HEALTH_CHECK = 'HealthCheck';
    const CONTRACT_PERIOD = 'ContractPeriod';

    private static function healthCheck(): ObjectType
    {
        return JsonSchema::object([
            'system' => JsonSchema::boolean()
        ]);
    }

    private static function contractPeriod(): ObjectType
    {
        return JsonSchema::object([
            ContractPeriod::START_DATE => Schema::startDate(),
            ContractPeriod::END_DATE => Schema::endDate(),
        ]);
    }
    
    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        $eventMachine->registerType(self::CONTRACT_PERIOD, self::contractPeriod());

        //Register the HealthCheck type returned by @see \App\Api\Query::HEALTH_CHECK
        $eventMachine->registerType(self::HEALTH_CHECK, self::healthCheck());
    }
}
