<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Contract;

use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class AddContractContext implements ImmutableRecord
{
    use ImmutableRecordLogic;

    const SUPERIOR_PARTY = 'superiorParty';
    const SUBORDINATE_PARTY = 'subordinateParty';

    /**
     * @var SuperiorParty
     */
    private $superiorParty;

    /**
     * @var SubordinateParty
     */
    private $subordinateParty;

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
}
