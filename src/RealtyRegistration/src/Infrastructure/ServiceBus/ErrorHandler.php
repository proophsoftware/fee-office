<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\ServiceBus;

use Prooph\Common\Event\ActionEvent;
use Prooph\ServiceBus\MessageBus;
use Prooph\ServiceBus\Plugin\Plugin;
use Prooph\ServiceBus\QueryBus;

final class ErrorHandler implements Plugin
{
    private $handler;

    public function attachToMessageBus(MessageBus $messageBus): void
    {
        //Register finalize handler that throws original exception before message bus throws a wrapped MessageDispatchException
        $this->handler = $messageBus->attach(MessageBus::EVENT_FINALIZE, function (ActionEvent $actionEvent): void {
            if ($exception = $actionEvent->getParam(MessageBus::EVENT_PARAM_EXCEPTION)) {
                if($deferred = $actionEvent->getParam(QueryBus::EVENT_PARAM_DEFERRED)) {
                    $deferred->reject($exception);
                    $actionEvent->setParam(MessageBus::EVENT_PARAM_EXCEPTION, null);
                    return;
                }

                throw $exception;
            }
        }, QueryBus::PRIORITY_PROMISE_REJECT + 100);
    }

    public function detachFromMessageBus(MessageBus $messageBus): void
    {
        $messageBus->detach($this->handler);
    }
}
