<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model;

use FeeOffice\ContractManagement\Api\Event;
use FeeOffice\ContractManagement\Api\Payload;
use FeeOffice\ContractManagement\Model\Contract\AddContractContext;
use FeeOffice\ContractManagement\Model\Contract\ContractId;
use FeeOffice\ContractManagement\Model\Contract\ContractPeriod;
use FeeOffice\ContractManagement\Model\Contract\EndDate;
use FeeOffice\ContractManagement\Model\Contract\StartDate;
use FeeOffice\ContractManagement\Model\Realty\ApartmentId;
use Prooph\EventMachine\Messaging\Message;

final class Contract
{
    public static function add(Message $addContract, AddContractContext $context): \Generator
    {
        $contractId = ContractId::fromString($addContract->get(Payload::CONTRACT_ID));
        $apartmentId = ApartmentId::fromString($addContract->get(Payload::APARTMENT_ID));
        $startDate = StartDate::fromString($addContract->get(Payload::START_DATE));
        $endDate = EndDate::fromString($addContract->get(Payload::END_DATE));
        $contractPeriod = ContractPeriod::fromTo($startDate, $endDate);

        yield [Event::CONTRACT_ADDED, [
            Payload::CONTRACT_ID => $contractId->toString(),
            Payload::APARTMENT_ID => $apartmentId->toString(),
            Payload::SUPERIOR_PARTY => $context->superiorParty()->toString(),
            Payload::SUBORDINATE_PARTY => $context->subordinateParty()->toString(),
            Payload::CONTRACT_PERIOD => $contractPeriod->toArray(),
        ]];
    }

    public static function whenContractAdded(Message $contractAdded): Contract\State
    {
        return Contract\State::fromArray([
            Contract\State::CONTRACT_ID => $contractAdded->get(Payload::CONTRACT_ID),
            Contract\State::APARTMENT_ID => $contractAdded->get(Payload::APARTMENT_ID),
            Contract\State::SUPERIOR_PARTY => $contractAdded->get(Payload::SUPERIOR_PARTY),
            Contract\State::SUBORDINATE_PARTY => $contractAdded->get(Payload::SUBORDINATE_PARTY),
            Contract\State::CONTRACT_PERIOD => $contractAdded->get(Payload::CONTRACT_PERIOD),
        ]);
    }
}
