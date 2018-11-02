<?php
declare(strict_types=1);

namespace App\Infrastructure\ModuleProxy;

use FeeOffice\ContactAdministration\Model\ContactCardCollection;
use Psr\Container\ContainerInterface;

final class ContractContactAdministrationProxyFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var ContactCardCollection $contactCardCollection */
        $contactCardCollection = $container->get(ContactCardCollection::class);

        return new ContractContactAdministrationProxy($contactCardCollection);
    }
}
