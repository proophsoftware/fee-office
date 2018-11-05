<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model\Exception;

use Fig\Http\Message\StatusCodeInterface;

final class InvalidIBAN extends \InvalidArgumentException
{
    public static function formatCheckFailed(string $iban): self
    {
        return new self(
            "IBAN is invalid. Got $iban",
            StatusCodeInterface::STATUS_BAD_REQUEST
        );
    }
}
