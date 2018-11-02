<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Infrastructure\System;

use Prooph\EventMachine\Container\ReflectionBasedContainer;
use Psr\Container\ContainerInterface;

final class ContactContextContainerFactory
{
    public function __invoke(ContainerInterface $appContainer): ContainerInterface
    {
        $config = $appContainer->get('config');

        if(!isset($config['contact'])) {
            throw new \RuntimeException("Missing contact service configuration in app container");
        }

        return new ReflectionBasedContainer(new ServiceFactory($config['contact']));
    }
}
