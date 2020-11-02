<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\CreateAndFetchEndpoint;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \Chimera\Mapping\Routing\CreateAndFetchEndpoint */
final class CreateAndFetchEndpointTest extends TestCase
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
        $annotation = new CreateAndFetchEndpoint(
            self::ENDPOINT_DATA + ['command' => 'test1', 'query' => 'test2', 'redirectTo' => 'test3']
        );

        $annotation->validate('class A');

        self::assertSame('test1', $annotation->command);
        self::assertSame('test2', $annotation->query);
        self::assertSame('test3', $annotation->redirectTo);
        self::assertSame(['POST'], $annotation->methods);
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
     * @param array{query?: string, command?: string, redirectTo?: string} $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values, string $expectedMessage): void
    {
        $annotation = new CreateAndFetchEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{query?: string, command?: string, redirectTo?: string}, 1: string}> */
    public function invalidScenarios(): iterable
    {
        yield 'missing command' => [
            ['query' => 'test', 'redirectTo' => 'test3'],
            '"command" of @Chimera\Mapping\Routing\CreateAndFetchEndpoint declared on class A expects string.',
        ];

        yield 'empty command' => [
            ['command' => '   ', 'query' => 'test', 'redirectTo' => 'test3'],
            '"command" of @Chimera\Mapping\Routing\CreateAndFetchEndpoint declared on class A expects string.',
        ];

        yield 'missing query' => [
            ['command' => 'test', 'redirectTo' => 'test3'],
            '"query" of @Chimera\Mapping\Routing\CreateAndFetchEndpoint declared on class A expects string.',
        ];

        yield 'empty query' => [
            ['command' => 'test', 'query' => '', 'redirectTo' => 'test3'],
            '"query" of @Chimera\Mapping\Routing\CreateAndFetchEndpoint declared on class A expects string.',
        ];

        yield 'missing redirectTo' => [
            ['command' => 'test', 'query' => 'test'],
            '"redirectTo" of @Chimera\Mapping\Routing\CreateAndFetchEndpoint declared on class A expects string.',
        ];

        yield 'empty redirectTo' => [
            ['command' => 'test', 'query' => 'test', 'redirectTo' => ''],
            '"redirectTo" of @Chimera\Mapping\Routing\CreateAndFetchEndpoint declared on class A expects string.',
        ];
    }
}
