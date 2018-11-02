<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Exception;

use FeeOffice\ContractManagement\Model\Contact\Contact;
use Fig\Http\Message\StatusCodeInterface;

final class NoBankAccount extends \InvalidArgumentException
{
    public static function forContact(Contact $contact): self
    {
        return new self(
            "A subordinate party should have a bank account. Given contact {$contact->contactId()} has none.",
            StatusCodeInterface::STATUS_BAD_REQUEST
        );
    }
}
