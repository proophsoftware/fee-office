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

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
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
}
