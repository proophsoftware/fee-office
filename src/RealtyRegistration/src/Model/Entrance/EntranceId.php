<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Model\Entrance;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class EntranceId
{
    private $entranceId;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $entranceId): self
    {
        return new self(Uuid::fromString($entranceId));
    }

    private function __construct(UuidInterface $entranceId)
    {
        $this->entranceId = $entranceId;
    }

    public function toString(): string
    {
        return $this->entranceId->toString();
    }

    public function equals($other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->entranceId->equals($other->entranceId);
    }

    public function __toString(): string
    {
        return $this->entranceId->toString();
    }
}
