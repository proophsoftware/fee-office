<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\System;

use App\Infrastructure;
use Prooph\EventMachine\Container\ReflectionBasedContainer;
use Prooph\EventMachine\EventMachine;
use Psr\Container\ContainerInterface;

final class EventMachineFactory
{
    public function __invoke(ContainerInterface $appContainer): EventMachine
    {
        $config = $appContainer->get('config');

        if(!isset($config['realty'])) {
            throw new \RuntimeException("Missing realty service configuration in app container");
        }

        return $this->bootstrapEventMachine($config['realty']);
    }

    private function bootstrapEventMachine(array $config): EventMachine
    {
        $serviceFactory = new ServiceFactory($config);

        //@TODO use cached serviceFactoryMap for production
        $container = new ReflectionBasedContainer(
            $serviceFactory,
            [
                \Prooph\EventMachine\EventMachine::SERVICE_ID_EVENT_STORE => \Prooph\EventStore\EventStore::class,
                \Prooph\EventMachine\EventMachine::SERVICE_ID_PROJECTION_MANAGER => \Prooph\EventStore\Projection\ProjectionManager::class,
                \Prooph\EventMachine\EventMachine::SERVICE_ID_COMMAND_BUS => Infrastructure\ServiceBus\CommandBus::class,
                \Prooph\EventMachine\EventMachine::SERVICE_ID_EVENT_BUS => Infrastructure\ServiceBus\EventBus::class,
                \Prooph\EventMachine\EventMachine::SERVICE_ID_QUERY_BUS => Infrastructure\ServiceBus\QueryBus::class,
                \Prooph\EventMachine\EventMachine::SERVICE_ID_DOCUMENT_STORE => \Prooph\EventMachine\Persistence\DocumentStore::class,
            ]
        );

        $serviceFactory->setContainer($container);

        $eventMachine = $serviceFactory->eventMachine();

        return $eventMachine->bootstrap($config['env'], $config['debug_mode']);
    }
}
