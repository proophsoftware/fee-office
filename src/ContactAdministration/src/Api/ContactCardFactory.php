<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use FeeOffice\ContactAdministration\Model\BankAccount;
use FeeOffice\ContactAdministration\Model\ContactCard;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ContactCardFactory
{
    public static function forPersonFromRequest(ServerRequestInterface $request): ContactCard
    {
        $data = $request->getParsedBody();

        self::assertIsset(ContactCard::CONTACT_CARD_ID, $data);
        self::assertIsset(ContactCard::FIRST_NAME, $data);
        self::assertIsset(ContactCard::LAST_NAME, $data);

        return ContactCard::forPerson(
            ContactCard\ContactCardId::fromString($data[ContactCard::CONTACT_CARD_ID]),
            ContactCard\FirstName::fromString($data[ContactCard::FIRST_NAME]),
            ContactCard\LastName::fromString($data[ContactCard::LAST_NAME])
        );
    }

    public static function forCompanyFromRequest(ServerRequestInterface $request): ContactCard
    {
        $data = $request->getParsedBody();

        self::assertIsset(ContactCard::CONTACT_CARD_ID, $data);
        self::assertIsset(ContactCard::COMPANY, $data);

        return ContactCard::forCompany(
            ContactCard\ContactCardId::fromString($data[ContactCard::CONTACT_CARD_ID]),
            ContactCard\Company::fromString($data[ContactCard::COMPANY])
        );
    }

    public static function bankAccountFromRequest(ServerRequestInterface $request): BankAccount
    {
        $data = $request->getParsedBody();

        self::assertIsset(BankAccount::IBAN, $data);
        self::assertIsset(BankAccount::BIC, $data);

        return BankAccount::fromIbanAndBic(
            BankAccount\IBAN::fromString($data[BankAccount::IBAN]),
            BankAccount\BIC::fromString($data[BankAccount::BIC])
        );
    }

    private static function assertIsset(string $key, array $reqData): void
    {
        if(!array_key_exists($key, $reqData)) {
            throw new \InvalidArgumentException("Missing $key", StatusCodeInterface::STATUS_BAD_REQUEST);
        }
    }
}
