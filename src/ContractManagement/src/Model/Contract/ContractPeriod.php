<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Contract;

use FeeOffice\ContractManagement\Model\Exception\InvalidContractPeriod;
use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class ContractPeriod implements ImmutableRecord
{
    use ImmutableRecordLogic;

    public const START_DATE = 'startDate';
    public const END_DATE = 'endDate';

    /**
     * @var StartDate
     */
    private $startDate;

    /**
     * @var EndDate
     */
    private $endDate;

    public static function fromTo(StartDate $startDate, EndDate $endDate): self
    {
        return self::fromRecordData([
            self::START_DATE => $startDate,
            self::END_DATE => $endDate,
        ]);
    }

    private function init()
    {
        if($this->endDate < $this->startDate) {
            throw InvalidContractPeriod::endBeforeStart($this->startDate, $this->endDate);
        }
    }

    /**
     * @return StartDate
     */
    public function startDate(): StartDate
    {
        return $this->startDate;
    }

    /**
     * @return EndDate
     */
    public function endDate(): EndDate
    {
        return $this->endDate;
    }
}
