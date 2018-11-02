<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Exception;

use FeeOffice\ContractManagement\Model\Contact\ContactId;
use Fig\Http\Message\StatusCodeInterface;

final class ContactNotFound extends \RuntimeException
{
    public static function withContactId(ContactId $contactId): self
    {
        return new self("ContactAdministration cannot find a contact with id: $contactId", StatusCodeInterface::STATUS_NOT_FOUND);
    }
}
