<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\ExecuteAndFetchEndpoint;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \Chimera\Mapping\Routing\ExecuteAndFetchEndpoint */
final class ExecuteAndFetchEndpointTest extends TestCase
{
    private const ENDPOINT_DATA = ['path' => '/tests', 'name' => 'test'];

    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validateAdditionalData()
     * @covers ::defaultMethods()
     * @covers \Chimera\Mapping\Validator
     * @covers \Chimera\Mapping\Routing\Endpoint
     */
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new ExecuteAndFetchEndpoint(self::ENDPOINT_DATA + ['command' => 'testing', 'query' => 'test']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->command);
        self::assertSame('test', $annotation->query);
        self::assertSame(['PUT', 'PATCH'], $annotation->methods);
    }

    /**
     * @test
     * @dataProvider invalidScenarios
     *
     * @covers ::__construct()
     * @covers ::validateAdditionalData()
     * @covers ::defaultMethods()
     * @covers \Chimera\Mapping\Validator
     * @covers \Chimera\Mapping\Routing\Endpoint
     *
     * @param array{query?: string, command?: string} $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values, string $expectedMessage): void
    {
        $annotation = new ExecuteAndFetchEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{query?: string, command?: string}, 1: string}> */
    public function invalidScenarios(): iterable
    {
        yield 'missing command' => [
            ['query' => 'test'],
            '"command" of @Chimera\Mapping\Routing\ExecuteAndFetchEndpoint declared on class A expects string.',
        ];

        yield 'empty command' => [
            ['command' => '', 'query' => 'test'],
            '"command" of @Chimera\Mapping\Routing\ExecuteAndFetchEndpoint declared on class A expects string.',
        ];

        yield 'missing query' => [
            ['command' => 'test'],
            '"query" of @Chimera\Mapping\Routing\ExecuteAndFetchEndpoint declared on class A expects string.',
        ];

        yield 'empty query' => [
            ['command' => 'test', 'query' => ''],
            '"query" of @Chimera\Mapping\Routing\ExecuteAndFetchEndpoint declared on class A expects string.',
        ];
    }
}
