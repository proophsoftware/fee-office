<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use FeeOffice\RealtyRegistration\Infrastructure\PreProcessor;
use FeeOffice\RealtyRegistration\Model\Building;
use FeeOffice\RealtyRegistration\Model\Entrance;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;

class Aggregate implements EventMachineDescription
{
    const BUILDING = 'Building';
    const ENTRANCE = 'Entrance';

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        self::describeBuilding($eventMachine);
        self::describeEntrance($eventMachine);
    }

    private static function describeBuilding(EventMachine $eventMachine): void
    {
        $eventMachine->process(Command::REGISTER_BUILDING)
            ->withNew(self::BUILDING)
            ->identifiedBy(Payload::BUILDING_ID)
            ->handle([Building::class, 'add'])
            ->recordThat(Event::BUILDING_REGISTERED)
            ->apply([Building::class, 'whenBuildingRegistered']);

        $eventMachine->process(Command::RENAME_BUILDING)
            ->withExisting(self::BUILDING)
            ->handle([Building::class, 'rename'])
            ->recordThat(Event::BUILDING_RENAMED)
            ->apply([Building::class, 'whenBuildingRenamed']);
    }

    private static function describeEntrance(EventMachine $eventMachine): void
    {
        $eventMachine->preProcess(Command::ADD_ENTRANCE, PreProcessor\AddEntrance::class);
        $eventMachine->process(Command::ADD_ENTRANCE)
            ->withNew(self::ENTRANCE)
            ->identifiedBy(Payload::ENTRANCE_ID)
            ->handle([Entrance::class, 'add'])
            ->recordThat(Event::ENTRANCE_ADDED)
            ->apply([Entrance::class, 'whenEntranceAdded']);
    }
}
