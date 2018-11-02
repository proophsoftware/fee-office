<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model;

use FeeOffice\ContactAdministration\Model\ContactCard\Company;
use FeeOffice\ContactAdministration\Model\ContactCard\ContactCardId;
use FeeOffice\ContactAdministration\Model\ContactCard\FirstName;
use FeeOffice\ContactAdministration\Model\ContactCard\LastName;
use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class ContactCard implements ImmutableRecord
{
    use ImmutableRecordLogic;

    const CONTACT_CARD_ID = 'contactCardId';
    const FIRST_NAME = 'firstName';
    const LAST_NAME = 'lastName';
    const COMPANY = 'company';
    const BANK_ACCOUNT = 'bankAccount';

    /**
     * @var ContactCardId
     */
    private $contactCardId;

    /**
     * @var FirstName|null
     */
    private $firstName;

    /**
     * @var LastName|null
     */
    private $lastName;

    /**
     * @var Company|null
     */
    private $company;

    /**
     * @var BankAccount|null
     */
    private $bankAccount;

    public static function forPerson(ContactCardId $cardId, FirstName $firstName, LastName $lastName): self
    {
        return self::fromRecordData([
            self::CONTACT_CARD_ID => $cardId,
            self::FIRST_NAME => $firstName,
            self::LAST_NAME => $lastName
        ]);
    }

    public static function forCompany(ContactCardId $cardId, Company $company): self
    {
        return self::fromRecordData([
            self::CONTACT_CARD_ID => $cardId,
            self::COMPANY => $company,
        ]);
    }

    public function withBankAccount(BankAccount $bankAccount): self
    {
        return $this->with([
            self::BANK_ACCOUNT => $bankAccount,
        ]);
    }

    /**
     * @return ContactCardId
     */
    public function contactCardId(): ContactCardId
    {
        return $this->contactCardId;
    }

    /**
     * @return FirstName|null
     */
    public function firstName(): ?FirstName
    {
        return $this->firstName;
    }

    /**
     * @return LastName|null
     */
    public function lastName(): ?LastName
    {
        return $this->lastName;
    }

    /**
     * @return Company|null
     */
    public function company(): ?Company
    {
        return $this->company;
    }

    /**
     * @return BankAccount|null
     */
    public function bankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }
}
