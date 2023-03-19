<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Routing\ExecuteAndFetchEndpoint;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;

#[PHPUnit\CoversClass(ExecuteAndFetchEndpoint::class)]
#[PHPUnit\CoversClass(Endpoint::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class ExecuteAndFetchEndpointTest extends TestCase
{
    private const ENDPOINT_DATA = ['path' => '/tests', 'name' => 'test'];

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new ExecuteAndFetchEndpoint(self::ENDPOINT_DATA + ['command' => 'testing', 'query' => 'test']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->command);
        self::assertSame('test', $annotation->query);
        self::assertSame(['PUT', 'PATCH'], $annotation->methods);
    }

    /** @param array{query?: string, command?: string} $values */
    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('invalidScenarios')]
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values, string $expectedMessage): void
    {
        $annotation = new ExecuteAndFetchEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{query?: string, command?: string}, 1: string}> */
    public static function invalidScenarios(): iterable
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
