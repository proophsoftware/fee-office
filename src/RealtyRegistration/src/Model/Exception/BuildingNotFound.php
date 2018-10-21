<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Exception;

use FeeOffice\RealtyRegistration\Model\Apartment\ApartmentId;
use FeeOffice\RealtyRegistration\Model\Building\BuildingId;
use FeeOffice\RealtyRegistration\Model\Entrance\EntranceId;
use Fig\Http\Message\StatusCodeInterface;

final class BuildingNotFound extends \RuntimeException
{
    public static function withBuildingId(BuildingId $buildingId): self
    {
        return new self("Building wih id $buildingId not found.", StatusCodeInterface::STATUS_NOT_FOUND);
    }

    public static function forEntrance(EntranceId $entranceId): self
    {
        return new self("Building for entrance with id $entranceId not found.", StatusCodeInterface::STATUS_NOT_FOUND);
    }

    public static function forApartment(ApartmentId $apartmentId): self
    {
        return new self("Building for apartment with id $apartmentId not found.", StatusCodeInterface::STATUS_NOT_FOUND);
    }
}
