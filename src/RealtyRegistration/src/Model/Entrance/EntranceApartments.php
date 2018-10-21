<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Entrance;

use FeeOffice\RealtyRegistration\Model\Apartment;

final class EntranceApartments
{
    /**
     * @var EntranceId
     */
    private $entranceId;

    /**
     * @var Apartment\State;
     */
    private $apartments;

    public function __construct(EntranceId $entranceId, Apartment\State ...$apartments)
    {
        array_walk($apartments, function (Apartment\State $apartment) use ($entranceId) {
            if(!$entranceId->equals($apartment->entranceId())) {
                throw new \RuntimeException("EntranceApartments for entrance: $entranceId contains invalid apartment."
                ." Apartment ({$apartment->apartmentId()}) entrance id: {$apartment->entranceId()} does not match.");
            }
        });

        $this->entranceId = $entranceId;
        $this->apartments = $apartments;
    }

    public function entranceId(): EntranceId
    {
        return $this->entranceId;
    }

    public function hasApartmentWith(Apartment\ApartmentNumber $apartmentNumber): bool
    {
        foreach ($this->apartments as $apartment) if($apartment->apartmentNumber()->equals($apartmentNumber)) return true;

        return false;
    }
}
