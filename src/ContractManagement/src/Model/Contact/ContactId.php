<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Contact;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ContactId
{
    private $contactId;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
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
