<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Contact;

use FeeOffice\ContractManagement\Model\Exception\ContactNotFound;

interface ContactAdministration
{
    /**
     * @return Contact[]
     * @throws ContactNotFound
     */
    public function getContacts(ContactId ...$ids): iterable;
}
