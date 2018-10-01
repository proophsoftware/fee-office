<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\Persistence\Stream;

class Projection implements EventMachineDescription
{
    /**
     * You can register aggregate and custom projections in event machine
     *
     * For custom projection you should define a unique projection name using a constant
     *
     * const USER_FRIENDS = 'UserFriends';
     */

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        /*
        $eventMachine->watch(Stream::ofWriteModel())
            ->withAggregateProjection(Aggregate::BUILDING);
        */
    }
}
