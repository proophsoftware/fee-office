<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model;

use FeeOffice\RealtyRegistration\Api\Event;
use FeeOffice\RealtyRegistration\Api\Payload;
use FeeOffice\RealtyRegistration\Model\Building\BuildingId;
use FeeOffice\RealtyRegistration\Model\Entrance\Address;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceId;
use Prooph\EventMachine\Messaging\Message;

final class Entrance
{
    public static function add(Message $addEntrance): \Generator
    {
        $entranceId = EntranceId::fromString($addEntrance->get(Payload::ENTRANCE_ID));
        $buildingId = BuildingId::fromString($addEntrance->get(Payload::BUILDING_ID));
        $address = Entrance\Address::fromString($addEntrance->get(Payload::ADDRESS));

        yield [Event::ENTRANCE_ADDED, [
            Payload::ENTRANCE_ID => $entranceId->toString(),
            Payload::BUILDING_ID => $buildingId->toString(),
            Payload::ADDRESS => $address->toString(),
        ]];
    }

    public static function correctAddress(Entrance\State $entrance, Message $correctAddress): \Generator
    {
        $address = Entrance\Address::fromString($correctAddress->get(Payload::ADDRESS));

        if($address->equals($entrance->address())) {
            yield null;
            return;
        }

        yield [
            Event::ENTRANCE_ADDRESS_CORRECTED, [
                Payload::ENTRANCE_ID => $entrance->entranceId()->toString(),
                Payload::ADDRESS => $address->toString(),
            ]
        ];
    }

    public static function whenEntranceAdded(Message $entranceAdded): Entrance\State
    {
        return Entrance\State::fromArray($entranceAdded->payload());
    }

    public static function whenEntranceAddressCorrected(Entrance\State $entrance, Message $entranceAddressCorrected): Entrance\State
    {
        return $entrance->with([
            Entrance\State::ADDRESS => Address::fromString($entranceAddressCorrected->get(Payload::ADDRESS))
        ]);
    }
}
