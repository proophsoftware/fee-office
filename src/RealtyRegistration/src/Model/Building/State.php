<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Building;

use FeeOffice\RealtyRegistration\Api\Payload;
use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class State implements ImmutableRecord
{
    use ImmutableRecordLogic;

    const BUILDING_ID = Payload::BUILDING_ID;
    const NAME = Payload::NAME;

    /**
     * @var BuildingId
     */
    private $buildingId;

    /**
     * @var string
     */
    private $name;

    /**
     * @return BuildingId
     */
    public function buildingId(): BuildingId
    {
        return $this->buildingId;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}
