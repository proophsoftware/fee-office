<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use FeeOffice\ContactAdministration\Model\BankAccount;
use FeeOffice\ContactAdministration\Model\ContactCard;
use Prooph\EventMachine\JsonSchema\JsonSchema;
use Prooph\EventMachine\JsonSchema\Type\ObjectType;

final class Type
{
    public const BANK_ACCOUNT = 'BankAccount';
    public const CONTACT_CARD = 'ContactCard';

    public static function definitions(): array
    {
        return [
            self::BANK_ACCOUNT => self::bankAccount(),
            self::CONTACT_CARD => self::contactCard(),

        ];
    }

    private static function bankAccount(): ObjectType
    {
        return JsonSchema::object([
            BankAccount::IBAN => Schema::iban(),
            BankAccount::BIC => Schema::bic(),
        ]);
    }

    private static function contactCard(): ObjectType
    {
        return JsonSchema::object([
            ContactCard::CONTACT_CARD_ID => Schema::contactCardId(),
            ContactCard::FIRST_NAME => JsonSchema::nullOr(Schema::firstName()),
            ContactCard::LAST_NAME => JsonSchema::nullOr(Schema::lastName()),
            ContactCard::COMPANY => JsonSchema::nullOr(Schema::company()),
            ContactCard::BANK_ACCOUNT => JsonSchema::nullOr(Schema::bankAccount()),
        ]);
    }
}
