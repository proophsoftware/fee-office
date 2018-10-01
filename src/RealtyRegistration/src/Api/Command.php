<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\JsonSchema\JsonSchema;

class Command implements EventMachineDescription
{
    const ADD_BUILDING = 'AddBuilding';
    const CHECK_IN_USER = 'CheckInUser';

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        $eventMachine->registerCommand(
            Command::ADD_BUILDING,
            JsonSchema::object(
                [
                    Payload::BUILDING_ID => Schema::buildingId(),
                    Payload::NAME => Schema::buildingName(),
                ]
            )
        );

        $eventMachine->registerCommand(
            Command::CHECK_IN_USER,
            JsonSchema::object([
                Payload::BUILDING_ID => Schema::buildingId(),
                Payload::NAME => Schema::username(),
            ])
        );
    }
}
