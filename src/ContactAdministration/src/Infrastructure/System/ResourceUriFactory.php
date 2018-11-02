<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Infrastructure\System;

use FeeOffice\ContactAdministration\ConfigProvider;
use FeeOffice\ContactAdministration\Model\ContactCard;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\Uri;

final class ResourceUriFactory
{
    public function forReadContactCard(ContactCard $contactCard): UriInterface
    {
        return new Uri(ConfigProvider::CONTACT_BASE_URL . ConfigProvider::URI_PATH_CONTACT_CARD . "/{$contactCard->contactCardId()}");
    }
}
