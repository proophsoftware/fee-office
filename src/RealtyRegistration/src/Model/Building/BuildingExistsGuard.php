<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Building;

interface BuildingExistsGuard
{
    public function isKnownBuilding(BuildingId $buildingId): bool;
}
