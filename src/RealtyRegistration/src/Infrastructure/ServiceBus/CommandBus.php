<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\ServiceBus;

use Prooph\EventMachine\Messaging\GenericJsonSchemaMessage;
use Prooph\ServiceBus\CommandBus as ProophCommandBus;

class CommandBus extends ProophCommandBus
{
    protected function getMessageName($message): string
    {
        if($message instanceof GenericJsonSchemaMessage) {
            return $message->messageName();
        }

        return parent::getMessageName($message);
    }
}
