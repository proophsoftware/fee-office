<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Apartment;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ApartmentId
{
    private $apartmentId;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $apartmentId): self
    {
        return new self(Uuid::fromString($apartmentId));
    }

    private function __construct(UuidInterface $apartmentId)
    {
        $this->apartmentId = $apartmentId;
    }

    public function toString(): string
    {
        return $this->apartmentId->toString();
    }

    public function equals($other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->apartmentId->equals($other->apartmentId);
    }

    public function __toString(): string
    {
        return $this->apartmentId->toString();
    }
}
