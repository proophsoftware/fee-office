<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model\Exception;

use FeeOffice\ContactAdministration\Model\ContactCard\ContactCardId;
use Fig\Http\Message\StatusCodeInterface;

final class ContactCardNotFound extends \RuntimeException
{
    public static function withCardId(ContactCardId $cardId): self
    {
        return new self("Contact card with id: $cardId not found.", StatusCodeInterface::STATUS_NOT_FOUND);
    }
}
