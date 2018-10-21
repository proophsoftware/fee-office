<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\JsonSchema\JsonSchema;

class Event implements EventMachineDescription
{
    const BUILDING_REGISTERED = Message::CTX.'BuildingRegistered';
    const BUILDING_RENAMED = Message::CTX.'BuildingRenamed';
    const ENTRANCE_ADDED = Message::CTX.'EntranceAdded';
    const ENTRANCE_ADDRESS_CORRECTED = Message::CTX.'EntranceAddressCorrected';
    const APARTMENT_ADDED = Message::CTX.'ApartmentAdded';

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        self::describeBuildingEvents($eventMachine);
        self::describeEntranceEvents($eventMachine);
        self::describeApartmentEvents($eventMachine);
    }

    private static function describeBuildingEvents(EventMachine $eventMachine): void
    {
        $eventMachine->registerEvent(
            self::BUILDING_REGISTERED,
            JsonSchema::object(
                [
                    Payload::BUILDING_ID => Schema::buildingId(),
                    Payload::NAME => Schema::buildingName(),
                ]
            )
        );

        $eventMachine->registerEvent(
            self::BUILDING_RENAMED,
            JsonSchema::object([
                Payload::BUILDING_ID => Schema::buildingId(),
                Payload::NAME => Schema::buildingName(),
            ])
        );
    }

    private static function describeEntranceEvents(EventMachine $eventMachine): void
    {
        $eventMachine->registerEvent(
            self::ENTRANCE_ADDED,
            JsonSchema::object([
                Payload::ENTRANCE_ID => Schema::entranceId(),
                Payload::BUILDING_ID => Schema::buildingId(),
                Payload::ADDRESS => Schema::entranceAddress(),
            ])
        );

        $eventMachine->registerEvent(
            self::ENTRANCE_ADDRESS_CORRECTED,
            JsonSchema::object([
                Payload::ENTRANCE_ID => Schema::entranceId(),
                Payload::ADDRESS => Schema::entranceAddress(),
            ])
        );
    }

    private static function describeApartmentEvents(EventMachine $eventMachine): void
    {
        $eventMachine->registerEvent(
            self::APARTMENT_ADDED,
            JsonSchema::object([
                Payload::APARTMENT_ID => Schema::apartmentId(),
                Payload::ENTRANCE_ID => Schema::entranceId(),
                Payload::APARTMENT_NUMBER => Schema::apartmentNumber(),
            ])
        );
    }
}
