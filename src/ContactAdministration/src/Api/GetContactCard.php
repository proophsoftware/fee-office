<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use App\Util\Swagger;
use FeeOffice\ContactAdministration\ConfigProvider;
use FeeOffice\ContactAdministration\Model\ContactCard\ContactCardId;
use FeeOffice\ContactAdministration\Model\ContactCardCollection;
use Fig\Http\Message\StatusCodeInterface;
use Prooph\EventMachine\JsonSchema\JsonSchema;
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
        $contactCardId = $request->getAttribute(ConfigProvider::REQ_PARAM_CONTACT_ID);

        if(!$contactCardId) {
            throw new \InvalidArgumentException("Missing contact card id", StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        $contactCard = $this->contactCardCollection->get(ContactCardId::fromString($contactCardId));

        return new JsonResponse($contactCard->toArray());
    }

    public static function schema(): array
    {
        return [
            Swagger::SUMMARY => 'Get a contact card',
            Swagger::TAGS => ['Contact Card'],
            Swagger::RESPONSES => [
                '200' => [
                    Swagger::DESCRIPTION => 'Ok',
                    Swagger::CONTENT => [
                        Swagger::APPLICATION_JSON => [
                            Swagger::SCHEMA => Swagger::jsonSchemaToOpenApiSchema(
                                JsonSchema::typeRef(Type::CONTACT_CARD)->toArray()
                            )
                        ]
                    ]
                ]
            ]
        ];
    }
}
