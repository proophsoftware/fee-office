<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model\Exception;

use Fig\Http\Message\StatusCodeInterface;

final class InvalidBIC extends \InvalidArgumentException
{
    public static function formatCheckFailed(string $bic): self
    {
        return new self(
            "Invalid BIC given. Got $bic",
            StatusCodeInterface::STATUS_BAD_REQUEST
        );
    }
}
