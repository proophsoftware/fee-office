<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Contract;

use FeeOffice\ContractManagement\Model\Realty\ApartmentId;
use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class State implements ImmutableRecord
{
    use ImmutableRecordLogic;

    public const CONTRACT_ID = 'contractId';
    public const APARTMENT_ID = 'apartmentId';
    public const SUPERIOR_PARTY = 'superiorParty';
    public const SUBORDINATE_PARTY = 'subordinateParty';
    public const CONTRACT_PERIOD = 'contractPeriod';

    /**
     * @var ContractId
     */
    private $contractId;

    /**
     * @var ApartmentId
     */
    private $apartmentId;

    /**
     * @var SuperiorParty
     */
    private $superiorParty;

    /**
     * @var SubordinateParty
     */
    private $subordinateParty;

    /**
     * @var ContractPeriod
     */
    private $contractPeriod;


    /**
     * @return ContractId
     */
    public function contractId(): ContractId
    {
        return $this->contractId;
    }

    /**
     * @return ApartmentId
     */
    public function apartmentId(): ApartmentId
    {
        return $this->apartmentId;
    }

    /**
     * @return SuperiorParty
     */
    public function superiorParty(): SuperiorParty
    {
        return $this->superiorParty;
    }

    /**
     * @return SubordinateParty
     */
    public function subordinateParty(): SubordinateParty
    {
        return $this->subordinateParty;
    }

    /**
     * @return ContractPeriod
     */
    public function contractPeriod(): ContractPeriod
    {
        return $this->contractPeriod;
    }
}
