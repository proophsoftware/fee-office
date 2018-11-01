<?php

declare(strict_types=1);

namespace FeeOffice\ContractManagement\Infrastructure\System;

use FeeOffice\ContractManagement\ConfigProvider;
use FeeOffice\ContractManagement\Http\MessageSchemaMiddleware;
use Psr\Container\ContainerInterface;

final class MessageSchemaMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): MessageSchemaMiddleware
    {
        return new MessageSchemaMiddleware($container->get(ConfigProvider::CONTRACT_EVENT_MACHINE));
    }
}
