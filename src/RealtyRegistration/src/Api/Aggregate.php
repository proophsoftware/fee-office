<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use FeeOffice\RealtyRegistration\Model\Building;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;

class Aggregate implements EventMachineDescription
{
    const BUILDING = 'Building';

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        $eventMachine->process(Command::ADD_BUILDING)
            ->withNew(self::BUILDING)
            ->identifiedBy(Payload::BUILDING_ID)
            ->handle([Building::class, 'add'])
            ->recordThat(Event::BUILDING_ADDED)
            ->apply([Building::class, 'whenBuildingAdded']);
    }
}
