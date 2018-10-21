<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model;

use FeeOffice\RealtyRegistration\Api\Event;
use FeeOffice\RealtyRegistration\Api\Payload;
use Prooph\EventMachine\Messaging\Message;

final class Building
{
    public static function add(Message $addBuilding): \Generator
    {
        yield [Event::BUILDING_REGISTERED, $addBuilding->payload()];
    }

    public static function rename(Building\State $building, Message $renameBuilding): \Generator
    {
        if($building->name() === $renameBuilding->get(Payload::NAME)) {
            yield null;
            return;
        }

        yield [Event::BUILDING_RENAMED, $renameBuilding->payload()];
    }

    public static function whenBuildingRegistered(Message $buildingRegistered): Building\State
    {
        return Building\State::fromArray($buildingRegistered->payload());
    }

    public static function whenBuildingRenamed(Building\State $building, Message $buildingRenamed): Building\State
    {
        return $building->with([
            Building\State::NAME => $buildingRenamed->get(Payload::NAME)
        ]);
    }
}
