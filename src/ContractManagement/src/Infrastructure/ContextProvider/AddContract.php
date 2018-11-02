<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Infrastructure\ContextProvider;

use FeeOffice\ContractManagement\Api\Command;
use FeeOffice\ContractManagement\Api\Payload;
use FeeOffice\ContractManagement\Model\Contact\ContactAdministration;
use FeeOffice\ContractManagement\Model\Contact\ContactId;
use FeeOffice\ContractManagement\Model\Contract\AddContractContext;
use FeeOffice\ContractManagement\Model\Contract\SubordinateParty;
use FeeOffice\ContractManagement\Model\Contract\SuperiorParty;
use Fig\Http\Message\StatusCodeInterface;
use Prooph\EventMachine\Aggregate\ContextProvider;
use Prooph\EventMachine\Messaging\Message;

final class AddContract implements ContextProvider
{
    /**
     * @var ContactAdministration
     */
    private $contactAdministration;

    public function __construct(ContactAdministration $contactAdministration)
    {
        $this->contactAdministration = $contactAdministration;
    }

    /**
     * @param Message $command
     * @return mixed The context passed as last argument to aggregate functions
     */
    public function provide(Message $command)
    {
        if($command->messageName() !== Command::ADD_CONTRACT) {
            throw new \RuntimeException(
                __METHOD__ . " can only provide context for " . Command::ADD_CONTRACT,
                StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR
            );
        }

        $superiorPartyId = ContactId::fromString($command->get(Payload::SUPERIOR_PARTY));
        $subordinatePartyId = ContactId::fromString($command->get(Payload::SUBORDINATE_PARTY));

        $superiorParty = null;
        $subordinateParty = null;

        foreach ($this->contactAdministration->getContacts($superiorPartyId, $subordinatePartyId) as $contact) {
            if($contact->contactId()->equals($superiorPartyId)) {
                $superiorParty = SuperiorParty::fromContact($contact);
            }

            if($contact->contactId()->equals($subordinatePartyId)) {
                $subordinateParty = SubordinateParty::fromContact($contact);
            }
        }

        if(!$superiorParty || !$subordinateParty) {
            throw new \RuntimeException(
                "ContactAdministration did not return contacts for all passed ids: $superiorPartyId, $subordinatePartyId",
                StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR
            );
        }

        return AddContractContext::fromRecordData([
            AddContractContext::SUPERIOR_PARTY => $superiorParty,
            AddContractContext::SUBORDINATE_PARTY => $subordinateParty,
        ]);
    }
}
