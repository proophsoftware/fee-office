<?php
declare(strict_types=1);

namespace App\Infrastructure\ModuleProxy;

use FeeOffice\ContractManagement\Model\Contact as Contract;
use FeeOffice\ContactAdministration\Model as ContactAdministration;
use FeeOffice\ContractManagement\Model\Exception\ContactNotFound;

final class ContractContactAdministrationProxy implements Contract\ContactAdministration
{
    /**
     * @var ContactAdministration\ContactCardCollection;
     */
    private $contactCardCollection;

    public function __construct(ContactAdministration\ContactCardCollection $cardCollection)
    {
        $this->contactCardCollection = $cardCollection;
    }

    /**
     * @inheritdoc
     */
    public function getContacts(Contract\ContactId ...$ids): iterable
    {
        foreach ($ids as $id) {
            $cardId = ContactAdministration\ContactCard\ContactCardId::fromString($id->toString());

            try {
                $contactCard = $this->contactCardCollection->get($cardId);

                yield Contract\Contact::fromArray([
                    Contract\Contact::CONTACT_ID => $cardId->toString(),
                    Contract\Contact::HAS_BANK_ACCOUNT => $contactCard->bankAccount() !== null,
                ]);
            } catch (ContactAdministration\Exception\ContactCardNotFound $exception) {
                throw ContactNotFound::withContactId($id);
            }
        }
    }
}
