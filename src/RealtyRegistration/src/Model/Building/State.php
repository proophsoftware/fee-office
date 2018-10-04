<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Building;

use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class State implements ImmutableRecord
{
    use ImmutableRecordLogic;

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
