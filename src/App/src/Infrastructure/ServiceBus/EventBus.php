<?php

declare(strict_types=1);

namespace App\Infrastructure\ServiceBus;

use Prooph\EventMachine\Messaging\GenericJsonSchemaMessage;
use Prooph\ServiceBus\EventBus as ProophEventBus;

class EventBus extends ProophEventBus
{
    protected function getMessageName($message): string
    {
        if($message instanceof GenericJsonSchemaMessage) {
            return $message->messageName();
        }

        return parent::getMessageName($message);
    }
}
