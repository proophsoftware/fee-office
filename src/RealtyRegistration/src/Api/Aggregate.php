<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use FeeOffice\RealtyRegistration\Infrastructure\PreProcessor;
use FeeOffice\RealtyRegistration\Infrastructure\ContextProvider;
use FeeOffice\RealtyRegistration\Model\Apartment;
use FeeOffice\RealtyRegistration\Model\Building;
use FeeOffice\RealtyRegistration\Model\Entrance;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;

class Aggregate implements EventMachineDescription
{
    const BUILDING = 'Building';
    const ENTRANCE = 'Entrance';
    const APARTMENT = 'Apartment';

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        self::describeBuilding($eventMachine);
        self::describeEntrance($eventMachine);
        self::describeApartment($eventMachine);
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

        $eventMachine->process(Command::CORRECT_ENTRANCE_ADDRESS)
            ->withExisting(self::ENTRANCE)
            ->handle([Entrance::class, 'correctAddress'])
            ->recordThat(Event::ENTRANCE_ADDRESS_CORRECTED)
            ->apply([Entrance::class, 'whenEntranceAddressCorrected']);
    }

    private static function describeApartment(EventMachine $eventMachine): void
    {
        $eventMachine->preProcess(Command::ADD_APARTMENT, PreProcessor\AddApartment::class);
        $eventMachine->process(Command::ADD_APARTMENT)
            ->withNew(self::APARTMENT)
            ->identifiedBy(Payload::APARTMENT_ID)
            ->provideContext(ContextProvider\AddApartment::class)
            ->handle([Apartment::class, 'add'])
            ->recordThat(Event::APARTMENT_ADDED)
            ->apply([Apartment::class, 'whenApartmentAdded']);

        $eventMachine->process(Command::ASSIGN_APARTMENT_ATTRIBUTE)
            ->withExisting(self::APARTMENT)
            ->handle([Apartment::class, 'assignApartmentAttribute'])
            ->recordThat(Event::APARTMENT_ATTRIBUTE_ASSIGNED)
            ->apply([Apartment::class, 'whenApartmentAttributeAssignedOrValueChanged'])
            ->orRecordThat(Event::APARTMENT_ATTRIBUTE_VALUE_CHANGED)
            ->apply([Apartment::class, 'whenApartmentAttributeAssignedOrValueChanged']);
    }
}
