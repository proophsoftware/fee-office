<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\JsonSchema\JsonSchema;

class Command implements EventMachineDescription
{
    const REGISTER_BUILDING = Message::CTX.'RegisterBuilding';
    const RENAME_BUILDING = Message::CTX.'RenameBuilding';
    const ADD_ENTRANCE = Message::CTX.'AddEntrance';
    const CORRECT_ENTRANCE_ADDRESS = Message::CTX.'CorrectEntranceAddress';
    const ADD_APARTMENT = Message::CTX.'AddApartment';
    const ASSIGN_APARTMENT_ATTRIBUTE = Message::CTX.'AssignApartmentAttribute';

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        self::describeBuildingCommands($eventMachine);
        self::describeEntranceCommands($eventMachine);
        self::describeApartmentCommands($eventMachine);
    }

    private static function describeBuildingCommands(EventMachine $eventMachine): void
    {
        $eventMachine->registerCommand(
            Command::REGISTER_BUILDING,
            JsonSchema::object(
                [
                    Payload::BUILDING_ID => Schema::buildingId(),
                    Payload::NAME => Schema::buildingName(),
                ]
            )
        );

        $eventMachine->registerCommand(
            Command::RENAME_BUILDING,
            JsonSchema::object([
                Payload::BUILDING_ID => Schema::buildingId(),
                Payload::NAME => Schema::buildingName(),
            ])
        );
    }

    private static function describeEntranceCommands(EventMachine $eventMachine): void
    {
        $eventMachine->registerCommand(
            Command::ADD_ENTRANCE,
            JsonSchema::object(
                [
                    Payload::ENTRANCE_ID => Schema::entranceId(),
                    Payload::BUILDING_ID => Schema::buildingId(),
                    Payload::ADDRESS => Schema::entranceAddress(),
                ]
            )
        );

        $eventMachine->registerCommand(
            Command::CORRECT_ENTRANCE_ADDRESS,
            JsonSchema::object([
                Payload::ENTRANCE_ID => Schema::entranceId(),
                Payload::ADDRESS => Schema::entranceAddress(),
            ])
        );
    }

    private static function describeApartmentCommands(EventMachine $eventMachine): void
    {
        $eventMachine->registerCommand(
            Command::ADD_APARTMENT,
            JsonSchema::object([
                Payload::APARTMENT_ID => Schema::apartmentId(),
                Payload::ENTRANCE_ID => Schema::entranceId(),
                Payload::APARTMENT_NUMBER => Schema::apartmentNumber(),
            ])
        );

        $eventMachine->registerCommand(
            Command::ASSIGN_APARTMENT_ATTRIBUTE,
            JsonSchema::object([
                Payload::APARTMENT_ID => Schema::apartmentId(),
                Payload::LABEL => Schema::apartmentAttributeLabelId(),
                Payload::VALUE => Schema::apartmentAttributeValue(),
            ])
        );
    }
}
