<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use FeeOffice\ContactAdministration\Model\ContactCard;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ContactCardFactory
{
    public static function forPersonFromRequest(ServerRequestInterface $request): ContactCard
    {
        $data = $request->getParsedBody();

        $contactCardId = $data[ContactCard::CONTACT_CARD_ID] ?? null;
        $firstName = $data[ContactCard::FIRST_NAME] ?? null;
        $lastName = $data[ContactCard::LAST_NAME] ?? null;

        if(!$contactCardId) {
            throw new \InvalidArgumentException("Missing contactCardId", StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        if(!$firstName) {
            throw new \InvalidArgumentException("Missing firstName", StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        if(!$lastName) {
            throw new \InvalidArgumentException("Missing lastName", StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        return ContactCard::forPerson(
            ContactCard\ContactCardId::fromString((string)$contactCardId),
            ContactCard\FirstName::fromString((string)$firstName),
            ContactCard\LastName::fromString((string)$lastName)
        );
    }

    public static function forCompanyFromRequest(ServerRequestInterface $request): ContactCard
    {
        $data = $request->getParsedBody();

        $contactCardId = $data[ContactCard::CONTACT_CARD_ID] ?? null;
        $company = $data[ContactCard::COMPANY] ?? null;

        if(!$contactCardId) {
            throw new \InvalidArgumentException("Missing contactCardId", StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        if(!$company) {
            throw new \InvalidArgumentException("Missing company", StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        return ContactCard::forCompany(
            ContactCard\ContactCardId::fromString((string)$contactCardId),
            ContactCard\Company::fromString((string)$company)
        );
    }
}
