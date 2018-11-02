<?php

declare(strict_types=1);

namespace FeeOffice\ContractManagement\Api;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\JsonSchema\JsonSchema;

class Command implements EventMachineDescription
{
    const ADD_CONTRACT = Message::CTX.'AddContract';

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        self::describeContractCommands($eventMachine);
    }

    private static function describeContractCommands(EventMachine $eventMachine): void
    {
        $eventMachine->registerCommand(self::ADD_CONTRACT, JsonSchema::object([
            Payload::CONTRACT_ID => Schema::contractId(),
            Payload::APARTMENT_ID => Schema::apartmentId(),
            Payload::SUPERIOR_PARTY => Schema::superiorParty(),
            Payload::SUBORDINATE_PARTY => Schema::subordinateParty(),
            Payload::START_DATE => Schema::startDate(),
            Payload::END_DATE => Schema::endDate(),
        ]));
    }
}
