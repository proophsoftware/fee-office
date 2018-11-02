<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Api;

use FeeOffice\ContractManagement\Model\Contract;
use FeeOffice\ContractManagement\Infrastructure\ContextProvider;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;

class Aggregate implements EventMachineDescription
{
    const CONTRACT = 'Contract';

    /**
     * @param EventMachine $eventMachine
     */
    public static function describe(EventMachine $eventMachine): void
    {
        self::describeContract($eventMachine);
    }

    private static function describeContract(EventMachine $eventMachine)
    {
        $eventMachine->process(Command::ADD_CONTRACT)
            ->withNew(self::CONTRACT)
            ->identifiedBy(Payload::CONTRACT_ID)
            ->provideContext(ContextProvider\AddContract::class)
            ->handle([Contract::class, 'add'])
            ->recordThat(Event::CONTRACT_ADDED)
            ->apply([Contract::class, 'whenContractAdded']);
    }
}
