<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Exception;

use FeeOffice\RealtyRegistration\Model\Apartment\ApartmentNumber;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceId;
use Fig\Http\Message\StatusCodeInterface;

final class DuplicateApartmentNumber extends \InvalidArgumentException
{
    public static function forEntrance(EntranceId $entranceId, ApartmentNumber $apartmentNumber): self
    {
        return new self(
            "Duplicate apartment number $apartmentNumber for entrance $entranceId.",
            StatusCodeInterface::STATUS_BAD_REQUEST
        );
    }
}
