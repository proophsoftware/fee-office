<?php

declare(strict_types=1);

namespace FeeOffice\ContractManagement\Api;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\JsonSchema\JsonSchema;

class Event implements EventMachineDescription
{
    public const CONTRACT_ADDED = Message::CTX.'ContractAdded';
    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        self::describeContractEvents($eventMachine);
    }

    private static function describeContractEvents(EventMachine $eventMachine)
    {
        $eventMachine->registerEvent(self::CONTRACT_ADDED, JsonSchema::object([
            Payload::CONTRACT_ID => Schema::contractId(),
            Payload::APARTMENT_ID => Schema::apartmentId(),
            Payload::SUPERIOR_PARTY => Schema::superiorParty(),
            Payload::SUBORDINATE_PARTY => Schema::subordinateParty(),
            Payload::CONTRACT_PERIOD => Schema::contractPeriod(),
        ]));
    }
}
