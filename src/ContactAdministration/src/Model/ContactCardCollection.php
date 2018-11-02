<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model;

use FeeOffice\ContactAdministration\Model\ContactCard\ContactCardId;
use FeeOffice\ContactAdministration\Model\Exception\ContactCardNotFound;

interface ContactCardCollection
{
    public function add(ContactCard $contactCard): void;

    public function replace(ContactCard $contactCard): void;

    public function remove(ContactCardId $cardId): void;

    /**
     * @throws ContactCardNotFound
     */
    public function get(ContactCardId $cardId): ContactCard;

    /**
     * @return ContactCard[]
     */
    public function getMany($limit = 100, $offset = 0): iterable;

    /**
     * Find cards by name.
     *
     * Name filter is applied as a case insensitive %LIKE% filter to "FIRSTNAME LASTNAME" (in case of person contact card)
     * or "COMPANY" for companies.
     *
     * @param string $name
     * @param int $limit
     * @param int $offset
     * @return ContactCard[]
     */
    public function findByName(string $name, $limit = 100, $offset = 0): iterable;
}
