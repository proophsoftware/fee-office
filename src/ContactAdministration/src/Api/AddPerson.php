<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use FeeOffice\ContactAdministration\Infrastructure\System\ResourceUriFactory;
use FeeOffice\ContactAdministration\Model\ContactCardCollection;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmptyResponse;

final class AddPerson implements RequestHandlerInterface
{
    /**
     * @var ContactCardCollection
     */
    private $contactCardCollection;

    /**
     * @var ResourceUriFactory
     */
    private $resourceUriFactory;

    public function __construct(ContactCardCollection $cardCollection, ResourceUriFactory $resourceUriFactory)
    {
        $this->contactCardCollection = $cardCollection;
        $this->resourceUriFactory = $resourceUriFactory;
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $contactCard = ContactCardFactory::forPersonFromRequest($request);

        $this->contactCardCollection->add($contactCard);

        return new EmptyResponse(StatusCodeInterface::STATUS_CREATED, [
            'Location' => (string)$this->resourceUriFactory->forReadContactCard($contactCard)
        ]);
    }
}
