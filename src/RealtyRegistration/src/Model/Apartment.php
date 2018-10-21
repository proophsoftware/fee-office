<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model;

use FeeOffice\RealtyRegistration\Api\Event;
use FeeOffice\RealtyRegistration\Api\Payload;
use FeeOffice\RealtyRegistration\Model\Apartment\ApartmentId;
use FeeOffice\RealtyRegistration\Model\Apartment\ApartmentNumber;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceApartments;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceId;
use FeeOffice\RealtyRegistration\Model\Exception\DuplicateApartmentNumber;
use Prooph\EventMachine\Messaging\Message;

final class Apartment
{
    public static function add(Message $addApartment, EntranceApartments $existingApartments): \Generator
    {
        $apartmentId = ApartmentId::fromString($addApartment->get(Payload::APARTMENT_ID));
        $entranceId = EntranceId::fromString($addApartment->get(Payload::ENTRANCE_ID));
        $apartmentNumber = ApartmentNumber::fromString($addApartment->get(Payload::APARTMENT_NUMBER));

        if(!$existingApartments->entranceId()->equals($entranceId)) {
            throw new \RuntimeException("Wrong Entrance Apartments given. Message entranceId: $entranceId does not match with entrance id of existing apartments");
        }

        //We are using eventual consistent, global state for this check. There is a small chance that it fails.
        //However, for current usage scenario of the application the check is secure enough and even if it would fail
        //we can easily identify and resolve the conflict manually.
        if($existingApartments->hasApartmentWith($apartmentNumber)) {
            throw DuplicateApartmentNumber::forEntrance($entranceId, $apartmentNumber);
        }

        yield [
            Event::APARTMENT_ADDED, [
                Payload::APARTMENT_ID => $apartmentId->toString(),
                Payload::ENTRANCE_ID => $entranceId->toString(),
                Payload::APARTMENT_NUMBER => $apartmentNumber->toString(),
            ]
        ];
    }

    public static function whenApartmentAdded(Message $apartmentAdded): Apartment\State
    {
        return Apartment\State::fromArray($apartmentAdded->payload());
    }
}
