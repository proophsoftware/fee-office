<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;

class Aggregate implements EventMachineDescription
{
    //const BUILDING = 'Building';

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {

    }
}
