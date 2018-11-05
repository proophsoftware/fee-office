<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use FeeOffice\ContactAdministration\Model\BankAccount;
use Prooph\EventMachine\JsonSchema\JsonSchema;
use Prooph\EventMachine\JsonSchema\Type\ObjectType;

final class Type
{
    public const BANK_ACCOUNT = 'BankAccount';

    public static function definitions(): array
    {
        return [
            self::BANK_ACCOUNT => self::bankAccount(),
        ];
    }

    private static function bankAccount(): ObjectType
    {
        return JsonSchema::object([
            BankAccount::IBAN => Schema::iban(),
            BankAccount::BIC => Schema::bic(),
        ]);
    }
}
