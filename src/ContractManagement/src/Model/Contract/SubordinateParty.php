<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Contract;

use FeeOffice\ContractManagement\Model\Contact\Contact;
use FeeOffice\ContractManagement\Model\Exception\NoBankAccount;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class SubordinateParty
{
    private $contactId;

    public static function fromContact(Contact $contact): self
    {
        if(!$contact->hasBankAccount()) {
            throw NoBankAccount::forContact($contact);
        }

        return self::fromString($contact->contactId()->toString());
    }

    public static function fromString(string $contactId): self
    {
        return new self(Uuid::fromString($contactId));
    }

    private function __construct(UuidInterface $contactId)
    {
        $this->contactId = $contactId;
    }

    public function toString(): string
    {
        return $this->contactId->toString();
    }

    public function equals($other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->contactId->equals($other->contactId);
    }

    public function __toString(): string
    {
        return $this->contactId->toString();
    }

}
