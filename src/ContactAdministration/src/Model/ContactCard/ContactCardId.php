<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model\ContactCard;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ContactCardId
{
    private $id;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $id): self
    {
        return new self(Uuid::fromString($id));
    }

    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function toString(): string
    {
        return $this->id->toString();
    }

    public function equals($other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->id->equals($other->id);
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }
}
