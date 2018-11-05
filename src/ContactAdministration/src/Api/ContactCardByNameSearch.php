<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Api;

use App\Util\Swagger;
use FeeOffice\ContactAdministration\ConfigProvider;
use FeeOffice\ContactAdministration\Model\ContactCardCollection;
use Fig\Http\Message\StatusCodeInterface;
use Prooph\EventMachine\JsonSchema\JsonSchema;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

final class ContactCardByNameSearch implements RequestHandlerInterface
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
        $nameFilter = $request->getQueryParams()['name'] ?? null;

        if(!$nameFilter) {
            throw new \InvalidArgumentException("Missing name filter", StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        $cards = $this->contactCardCollection->findByName((string)$nameFilter);

        $cardList = [];

        foreach ($cards as $card) {
            $cardList[] = $card->toArray();
        }

        return new JsonResponse($cardList);
    }

    public static function schema(): array
    {
        return [
            Swagger::SUMMARY => 'Query contact cards by person or company name',
            Swagger::TAGS => ['Contact Card'],
            Swagger::PARAMETERS => [
                [
                    Swagger::PARAM_NAME => 'name',
                    Swagger::PARAM_IN => Swagger::QUERY,
                    Swagger::REQUIRED => true,
                    Swagger::SCHEMA => Swagger::jsonSchemaToOpenApiSchema(
                        JsonSchema::string()->withMinLength(1)->toArray()
                    )
                ],
            ],
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
