<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Exception;

use FeeOffice\ContractManagement\Model\Contract\EndDate;
use FeeOffice\ContractManagement\Model\Contract\StartDate;
use Fig\Http\Message\StatusCodeInterface;

final class InvalidContractPeriod extends \InvalidArgumentException
{
    public static function endBeforeStart(StartDate $startDate, EndDate $endDate): self
    {
        return new self(
            "Invalid contract period. End date ($endDate) is before start date ($startDate).",
            StatusCodeInterface::STATUS_BAD_REQUEST
        );
    }
}
