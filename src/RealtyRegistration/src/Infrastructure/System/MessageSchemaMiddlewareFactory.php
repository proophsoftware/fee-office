<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Infrastructure\System;

use FeeOffice\RealtyRegistration\ConfigProvider;
use FeeOffice\RealtyRegistration\Http\MessageSchemaMiddleware;
use Psr\Container\ContainerInterface;

final class MessageSchemaMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): MessageSchemaMiddleware
    {
        return new MessageSchemaMiddleware($container->get(ConfigProvider::REALTY_EVENT_MACHINE));
    }
}
