<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Routing\ExecuteEndpoint;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;

#[PHPUnit\CoversClass(ExecuteEndpoint::class)]
#[PHPUnit\CoversClass(Endpoint::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class ExecuteEndpointTest extends TestCase
{
    private const ENDPOINT_DATA = ['path' => '/tests', 'name' => 'test'];

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new ExecuteEndpoint(self::ENDPOINT_DATA + ['command' => 'testing', 'async' => true]);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->command);
        self::assertSame(['PUT', 'PATCH', 'DELETE'], $annotation->methods);
        self::assertTrue($annotation->async);
    }

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenAsyncNotIsProvided(): void
    {
        $annotation = new ExecuteEndpoint(self::ENDPOINT_DATA + ['command' => 'testing']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->command);
        self::assertSame(['PUT', 'PATCH', 'DELETE'], $annotation->methods);
        self::assertFalse($annotation->async);
    }

    /** @param array{command?: string, async?: bool} $values */
    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('invalidScenarios')]
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values, string $expectedMessage): void
    {
        $annotation = new ExecuteEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{command?: string, async?: bool}, 1: string}> */
    public static function invalidScenarios(): iterable
    {
        yield 'missing command' => [
            [],
            '"command" of @Chimera\Mapping\Routing\ExecuteEndpoint declared on class A expects string.',
        ];

        yield 'empty command' => [
            ['command' => ''],
            '"command" of @Chimera\Mapping\Routing\ExecuteEndpoint declared on class A expects string.',
        ];
    }
}
