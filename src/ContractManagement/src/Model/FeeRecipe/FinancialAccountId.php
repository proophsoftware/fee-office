<?php

declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\FeeRecipe;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class FinancialAccountId
{
    private $accountId;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $accountId): self
    {
        return new self(Uuid::fromString($accountId));
    }

    private function __construct(UuidInterface $accountId)
    {
        $this->accountId = $accountId;
    }

    public function toString(): string
    {
        return $this->accountId->toString();
    }

    public function equals($other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->accountId->equals($other->accountId);
    }

    public function __toString(): string
    {
        return $this->accountId->toString();
    }
}
