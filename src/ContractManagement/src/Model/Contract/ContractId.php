<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Contract;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ContractId
{
    private $contractId;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $contractId): self
    {
        return new self(Uuid::fromString($contractId));
    }

    private function __construct(UuidInterface $contractId)
    {
        $this->contractId = $contractId;
    }

    public function toString(): string
    {
        return $this->contractId->toString();
    }

    public function equals($other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->contractId->equals($other->contractId);
    }

    public function __toString(): string
    {
        return $this->contractId->toString();
    }
}
