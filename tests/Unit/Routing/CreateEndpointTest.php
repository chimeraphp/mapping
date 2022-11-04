<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\CreateEndpoint;
use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;

#[PHPUnit\CoversClass(CreateEndpoint::class)]
#[PHPUnit\CoversClass(Endpoint::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class CreateEndpointTest extends TestCase
{
    private const ENDPOINT_DATA = ['path' => '/tests', 'name' => 'test'];

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new CreateEndpoint(
            self::ENDPOINT_DATA + ['command' => 'testing', 'redirectTo' => 'test', 'async' => true],
        );

        $annotation->validate('class A');

        self::assertSame('testing', $annotation->command);
        self::assertSame('test', $annotation->redirectTo);
        self::assertSame(['POST'], $annotation->methods);
        self::assertTrue($annotation->async);
    }

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenAsyncNotIsProvided(): void
    {
        $annotation = new CreateEndpoint(self::ENDPOINT_DATA + ['command' => 'testing', 'redirectTo' => 'test']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->command);
        self::assertSame('test', $annotation->redirectTo);
        self::assertSame(['POST'], $annotation->methods);
        self::assertFalse($annotation->async);
    }

    /** @param array{command?: string, redirectTo?: string} $values */
    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('invalidScenarios')]
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values, string $expectedMessage): void
    {
        $annotation = new CreateEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{command?: string, redirectTo?: string}, 1: string}> */
    public static function invalidScenarios(): iterable
    {
        yield 'missing command' => [
            ['redirectTo' => 'test'],
            '"command" of @Chimera\Mapping\Routing\CreateEndpoint declared on class A expects string.',
        ];

        yield 'empty command' => [
            ['command' => '', 'redirectTo' => 'test'],
            '"command" of @Chimera\Mapping\Routing\CreateEndpoint declared on class A expects string.',
        ];

        yield 'missing redirectTo' => [
            ['command' => 'test'],
            '"redirectTo" of @Chimera\Mapping\Routing\CreateEndpoint declared on class A expects string.',
        ];

        yield 'empty redirectTo' => [
            ['command' => 'test', 'redirectTo' => ''],
            '"redirectTo" of @Chimera\Mapping\Routing\CreateEndpoint declared on class A expects string.',
        ];
    }
}
