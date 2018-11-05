<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use FeeOffice\ContactAdministration\Model\BankAccount\BIC;
use FeeOffice\ContactAdministration\Model\BankAccount\IBAN;
use Prooph\EventMachine\JsonSchema\JsonSchema;
use Prooph\EventMachine\JsonSchema\Type\StringType;
use Prooph\EventMachine\JsonSchema\Type\TypeRef;
use Prooph\EventMachine\JsonSchema\Type\UuidType;

final class Schema
{
    public static function contactCardId(): UuidType
    {
        return JsonSchema::uuid();
    }

    public static function firstName(): StringType
    {
        return JsonSchema::string()->withMinLength(1);
    }

    public static function lastName(): StringType
    {
        return JsonSchema::string()->withMinLength(1);
    }

    public static function company(): StringType
    {
        return JsonSchema::string()->withMinLength(1);
    }

    public static function bankAccount(): TypeRef
    {
        return JsonSchema::typeRef(Type::BANK_ACCOUNT);
    }

    public static function iban(): StringType
    {
        return JsonSchema::string()->withPattern(IBAN::VALID_PATTERN);
    }

    public static function bic(): StringType
    {
        return JsonSchema::string()->withPattern(BIC::VALID_PATTERN);
    }
}
