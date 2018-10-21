<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Entrance;

use FeeOffice\RealtyRegistration\Api\Payload;
use FeeOffice\RealtyRegistration\Model\Building\BuildingId;
use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class State implements ImmutableRecord
{
    use ImmutableRecordLogic;

    const ENTRANCE_ID = Payload::ENTRANCE_ID;
    const BUILDING_ID = Payload::BUILDING_ID;
    const ADDRESS = Payload::ADDRESS;

    /**
     * @var EntranceId
     */
    private $entranceId;

    /**
     * @var BuildingId
     */
    private $buildingId;

    /**
     * @var Address
     */
    private $address;

    /**
     * @return EntranceId
     */
    public function entranceId(): EntranceId
    {
        return $this->entranceId;
    }

    /**
     * @return BuildingId
     */
    public function buildingId(): BuildingId
    {
        return $this->buildingId;
    }

    /**
     * @return Address
     */
    public function address(): Address
    {
        return $this->address;
    }
}
