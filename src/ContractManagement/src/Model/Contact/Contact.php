<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Contact;

use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class Contact implements ImmutableRecord
{
    use ImmutableRecordLogic;

    const CONTACT_ID = 'contactId';
    const HAS_BANK_ACCOUNT = 'hasBankAccount';

    /**
     * @var ContactId
     */
    private $contactId;

    /**
     * @var bool
     */
    private $hasBankAccount;

    /**
     * @return ContactId
     */
    public function contactId(): ContactId
    {
        return $this->contactId;
    }

    /**
     * @return bool
     */
    public function hasBankAccount(): bool
    {
        return $this->hasBankAccount;
    }
}
