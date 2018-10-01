<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\JsonSchema\JsonSchema;

class Event implements EventMachineDescription
{
    const BUILDING_ADDED = 'BuildingAdded';
    const USER_CHECKED_IN = 'UserCheckedIn';

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        $eventMachine->registerEvent(
            self::BUILDING_ADDED,
            JsonSchema::object(
                [
                    Payload::BUILDING_ID => Schema::buildingId(),
                    Payload::NAME => Schema::buildingName(),
                ]
            )
        );

        $eventMachine->registerEvent(
            self::USER_CHECKED_IN,
            JsonSchema::object([
                Payload::BUILDING_ID => Schema::buildingId(),
                Payload::NAME => Schema::username(),
            ])
        );
    }
}
