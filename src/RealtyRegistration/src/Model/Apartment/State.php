<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Apartment;

use FeeOffice\RealtyRegistration\Api\Payload;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceId;
use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class State implements ImmutableRecord
{
    use ImmutableRecordLogic;

    const APARTMENT_ID = Payload::APARTMENT_ID;
    const ENTRANCE_ID = Payload::ENTRANCE_ID;
    const APARTMENT_NUMBER = Payload::APARTMENT_NUMBER;

    /**
     * @var ApartmentId
     */
    private $apartmentId;

    /**
     * @var EntranceId
     */
    private $entranceId;

    /**
     * @var ApartmentNumber
     */
    private $apartmentNumber;

    /**
     * @return ApartmentId
     */
    public function apartmentId(): ApartmentId
    {
        return $this->apartmentId;
    }

    /**
     * @return EntranceId
     */
    public function entranceId(): EntranceId
    {
        return $this->entranceId;
    }

    /**
     * @return ApartmentNumber
     */
    public function apartmentNumber(): ApartmentNumber
    {
        return $this->apartmentNumber;
    }
}
