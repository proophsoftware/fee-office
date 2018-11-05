<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use App\Util\Swagger;
use FeeOffice\ContactAdministration\Infrastructure\System\ResourceUriFactory;
use FeeOffice\ContactAdministration\Model\ContactCard;
use FeeOffice\ContactAdministration\Model\ContactCardCollection;
use Fig\Http\Message\StatusCodeInterface;
use Prooph\EventMachine\JsonSchema\JsonSchema;
use Prooph\EventMachine\JsonSchema\Type\ObjectType;
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

    public static function schema(): array
    {
        return [
            Swagger::SUMMARY => 'Add person contact card',
            Swagger::TAGS => ['Contact Card'],
            Swagger::REQUEST_BODY => [
                Swagger::DESCRIPTION => 'Subset of contact card properties',
                Swagger::REQUIRED => true,
                Swagger::CONTENT => [
                    Swagger::APPLICATION_JSON => [
                        Swagger::SCHEMA => Swagger::jsonSchemaToOpenApiSchema(
                            JsonSchema::object([
                                ContactCard::CONTACT_CARD_ID => Schema::contactCardId(),
                                ContactCard::FIRST_NAME => Schema::firstName(),
                                ContactCard::LAST_NAME => Schema::lastName(),
                            ])->toArray()
                        )
                    ]
                ]
            ],
            Swagger::RESPONSES => [
                '201' => [
                    Swagger::DESCRIPTION => 'Created',
                    Swagger::HEADERS => [
                        'Location' => [
                            Swagger::SCHEMA => Swagger::jsonSchemaToOpenApiSchema(JsonSchema::string()->toArray()),
                            Swagger::DESCRIPTION => 'Provides resource URL to newly created contact card'
                        ]
                    ]
                ]
            ]
        ];
    }
}
