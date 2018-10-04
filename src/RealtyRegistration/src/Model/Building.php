<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model;

use FeeOffice\RealtyRegistration\Api\Event;
use FeeOffice\RealtyRegistration\Model\Building\State;
use Prooph\EventMachine\Messaging\Message;

final class Building
{
    public static function add(Message $addBuilding): \Generator
    {
        yield [Event::BUILDING_ADDED, $addBuilding->payload()];
    }

    public static function whenBuildingAdded(Message $buildingAdded): State
    {
        return State::fromArray($buildingAdded->payload());
    }
}
