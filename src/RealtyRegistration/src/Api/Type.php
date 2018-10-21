<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

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
    const BUILDING = 'Building';
    const BUILDING_LIST_ITEM = 'BuildingListItem';
    const APARTMENT_ATTRIBUTE_LABEL = 'ApartmentAttributeLabel';

    private static function healthCheck(): ObjectType
    {
        return JsonSchema::object([
            'system' => JsonSchema::boolean()
        ]);
    }
    
    private static function building(): ObjectType
    {
        return JsonSchema::object([
            Building\State::BUILDING_ID => Schema::buildingId(),
            Building\State::NAME => Schema::buildingName(),
            Resolver\Building::ENTRANCES => JsonSchema::array(self::entrance()),
        ]);
    }

    private static function buildingListItem(): ObjectType
    {
        return JsonSchema::object([
            Building\State::BUILDING_ID => Schema::buildingId(),
            Building\State::NAME => Schema::buildingName(),
        ]);
    }
    
    private static function entrance(): ObjectType
    {
        return JsonSchema::object([
            Entrance\State::ENTRANCE_ID => Schema::entranceId(),
            Entrance\State::ADDRESS => Schema::entranceAddress(),
            Resolver\Building::APARTMENTS => JsonSchema::array(self::apartment()),
        ]);
    }

    private static function apartment(): ObjectType
    {
        return JsonSchema::object([
            Apartment\State::APARTMENT_ID => Schema::apartmentId(),
            Apartment\State::APARTMENT_NUMBER => Schema::apartmentNumber(),
        ]);
    }

    private static function apartmentAttributeLabel(): ObjectType
    {
        return JsonSchema::object([
            Apartment\ApartmentAttribute::LABEL_ID => Schema::apartmentAttributeLabelId(),
            Apartment\ApartmentAttribute::LABEL => Schema::apartmentAttributeLabelValue(),
        ]);
    }

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        //Register the HealthCheck type returned by @see \App\Api\Query::HEALTH_CHECK
        $eventMachine->registerType(self::HEALTH_CHECK, self::healthCheck());

        $eventMachine->registerType(self::BUILDING, self::building());

        $eventMachine->registerType(self::BUILDING_LIST_ITEM, self::buildingListItem());

        $eventMachine->registerType(self::APARTMENT_ATTRIBUTE_LABEL, self::apartmentAttributeLabel());
    }
}
