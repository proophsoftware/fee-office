<?php

declare(strict_types=1);

namespace App\Infrastructure\ServiceBus;

use Prooph\EventMachine\Messaging\GenericJsonSchemaMessage;
use Prooph\ServiceBus\QueryBus as ProophQueryBus;

class QueryBus extends ProophQueryBus
{
    protected function getMessageName($message): string
    {
        if($message instanceof GenericJsonSchemaMessage) {
            return $message->messageName();
        }

        return parent::getMessageName($message);
    }
}
