<?php
declare(strict_types=1);

namespace AppTest\Util;

use App\Util\Swagger;
use App\Util\Swagger\Method;
use App\Util\Swagger\Operation;
use PHPUnit\Framework\TestCase;

final class SwaggerTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideFastRouteToPathCases
     * @param string $fastRoute
     * @param string $method
     * @param Operation[] $expectedOperations
     */
    public function it_converts_fast_route_to_path(string $fastRoute, string $method, array $expectedOperations)
    {
        $operations = Swagger::fastRouteToOperations($fastRoute, $method);

        foreach ($operations as $k => $operation) {
            $expectedOperation = $expectedOperations[$k];
            $this->assertEquals($expectedOperation->toArray(), $operation->toArray(), "Operation at index $k does not match with expected operation at same index.");
        }
    }

    public function provideFastRouteToPathCases(): array
    {
        return [
            //#0
            [
                '/route/without/parameters',
                'GET',
                [
                    Operation::fromRecordData([
                        Operation::PATH => Swagger\Path::fromString('/route/without/parameters'),
                        Operation::METHOD => Method::fromString(Method::GET),
                        Operation::PARAMETERS => []
                    ]),
                ]
            ],
            //#1
            [
                '/route/with/parameter/{userid:[\d]+}',
                'GET',
                [
                    Operation::fromRecordData([
                        Operation::PATH => Swagger\Path::fromString('/route/with/parameter/{userid}'),
                        Operation::METHOD => Method::fromString(Method::GET),
                        Operation::PARAMETERS => [
                            Swagger\Parameter::fromArray([Swagger\Parameter::NAME => 'userid', Swagger\Parameter::PATTERN => '[\d]+'])
                        ]
                    ]),
                ]
            ],
            //#2
            [
                '/route/with/two/parameters/{userid:[\d]+}/and/{commentId:[a-zA-Z .-]+}',
                'PUT',
                [
                    Operation::fromRecordData([
                        Operation::PATH => Swagger\Path::fromString('/route/with/two/parameters/{userid}/and/{commentId}'),
                        Operation::METHOD => Method::fromString(Method::PUT),
                        Operation::PARAMETERS => [
                            Swagger\Parameter::fromArray([Swagger\Parameter::NAME => 'userid', Swagger\Parameter::PATTERN => '[\d]+']),
                            Swagger\Parameter::fromArray([Swagger\Parameter::NAME => 'commentId', Swagger\Parameter::PATTERN => '[a-zA-Z .-]+']),
                        ]
                    ]),
                ]
            ],
            //#3
            [
                '/route/with/two/parameters/{userid:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}/and/{commentId:[a-zA-Z .-]+}',
                'PUT',
                [
                    Operation::fromRecordData([
                        Operation::PATH => Swagger\Path::fromString('/route/with/two/parameters/{userid}/and/{commentId}'),
                        Operation::METHOD => Method::fromString(Method::PUT),
                        Operation::PARAMETERS => [
                            Swagger\Parameter::fromArray([Swagger\Parameter::NAME => 'userid', Swagger\Parameter::PATTERN => '[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}']),
                            Swagger\Parameter::fromArray([Swagger\Parameter::NAME => 'commentId', Swagger\Parameter::PATTERN => '[a-zA-Z .-]+']),
                        ]
                    ]),
                ]
            ],
            //#4
            [
                '/route/with/two/parameters/{userid:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}/and[/optional/{commentId:[a-zA-Z .-]+}]',
                'GET',
                [
                    Operation::fromRecordData([
                        Operation::PATH => Swagger\Path::fromString('/route/with/two/parameters/{userid}/and'),
                        Operation::METHOD => Method::fromString(Method::GET),
                        Operation::PARAMETERS => [
                            Swagger\Parameter::fromArray([Swagger\Parameter::NAME => 'userid', Swagger\Parameter::PATTERN => '[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}']),
                        ]
                    ]),
                    Operation::fromRecordData([
                        Operation::PATH => Swagger\Path::fromString('/route/with/two/parameters/{userid}/and/optional/{commentId}'),
                        Operation::METHOD => Method::fromString(Method::GET),
                        Operation::PARAMETERS => [
                            Swagger\Parameter::fromArray([Swagger\Parameter::NAME => 'userid', Swagger\Parameter::PATTERN => '[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}']),
                            Swagger\Parameter::fromArray([Swagger\Parameter::NAME => 'commentId', Swagger\Parameter::PATTERN => '[a-zA-Z .-]+']),
                        ]
                    ])
                ]

            ],
        ];
    }
}
