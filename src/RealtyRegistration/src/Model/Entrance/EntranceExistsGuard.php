<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Entrance;

interface EntranceExistsGuard
{
    public function isKnownEntrance(EntranceId $entranceId): bool;
}
