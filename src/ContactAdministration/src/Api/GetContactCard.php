<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use FeeOffice\ContactAdministration\Model\ContactCard\ContactCardId;
use FeeOffice\ContactAdministration\Model\ContactCardCollection;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

final class GetContactCard implements RequestHandlerInterface
{
    /**
     * @var ContactCardCollection
     */
    private $contactCardCollection;

    public function __construct(ContactCardCollection $cardCollection)
    {
        $this->contactCardCollection = $cardCollection;
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $contactCardId = $request->getAttribute('id');

        if(!$contactCardId) {
            throw new \InvalidArgumentException("Missing contact card id", StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        $contactCard = $this->contactCardCollection->get(ContactCardId::fromString($contactCardId));

        return new JsonResponse($contactCard->toArray());
    }
}
