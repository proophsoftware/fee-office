<?php
declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\System;

use FeeOffice\RealtyRegistration\ConfigProvider;
use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\Http\MessageBox;
use Psr\Container\ContainerInterface;

final class MessageBoxFactory
{
    public function __invoke(ContainerInterface $container): MessageBox
    {
        /** @var EventMachine $eventMachine */
        $eventMachine = $container->get(ConfigProvider::REALTY_EVENT_MACHINE);
        return $eventMachine->httpMessageBox();
    }
}
