<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Routing\FetchEndpoint;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;

#[PHPUnit\CoversClass(FetchEndpoint::class)]
#[PHPUnit\CoversClass(Endpoint::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class FetchEndpointTest extends TestCase
{
    private const ENDPOINT_DATA = ['path' => '/tests', 'name' => 'test'];

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new FetchEndpoint(self::ENDPOINT_DATA + ['query' => 'testing']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->query);
        self::assertSame(['GET'], $annotation->methods);
    }

    /** @param array{query?: string} $values */
    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('invalidScenarios')]
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values, string $expectedMessage): void
    {
        $annotation = new FetchEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{query?: string}, 1: string}> */
    public static function invalidScenarios(): iterable
    {
        yield 'missing query' => [
            [],
            '"query" of @Chimera\Mapping\Routing\FetchEndpoint declared on class A expects string.',
        ];

        yield 'empty query' => [
            ['query' => ''],
            '"query" of @Chimera\Mapping\Routing\FetchEndpoint declared on class A expects string.',
        ];
    }
}
