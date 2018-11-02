<?php

declare(strict_types=1);

namespace App;

use App\Infrastructure\ModuleProxy\ContractContactAdministrationProxyFactory;
use FeeOffice\ContractManagement\Model\Contact as ContractContact;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
                ContractContact\ContactAdministration::class => ContractContactAdministrationProxyFactory::class,
            ],
            'delegators' => [
                \Zend\Expressive\Application::class => [
                    \Zend\Expressive\Container\ApplicationConfigInjectionDelegator::class,
                ],
            ],
        ];
    }
}
