<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Infrastructure\System;

use FeeOffice\ContactAdministration\ConfigProvider;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

final class AbstractServiceFactory implements AbstractFactoryInterface
{
    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        /** @var ContainerInterface $contextContainer */
        $contextContainer = $container->get(ConfigProvider::SERVICE_CONTACT_CONTAINER);

        return $contextContainer->has($requestedName);
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ContainerInterface $contextContainer */
        $contextContainer = $container->get(ConfigProvider::SERVICE_CONTACT_CONTAINER);

        return $contextContainer->get($requestedName);
    }
}
