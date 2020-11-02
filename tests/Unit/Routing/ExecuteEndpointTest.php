<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\ExecuteEndpoint;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \Chimera\Mapping\Routing\ExecuteEndpoint */
final class ExecuteEndpointTest extends TestCase
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
        $annotation = new ExecuteEndpoint(self::ENDPOINT_DATA + ['command' => 'testing', 'async' => true]);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->command);
        self::assertSame(['PUT', 'PATCH', 'DELETE'], $annotation->methods);
        self::assertTrue($annotation->async);
    }

    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validateAdditionalData()
     * @covers ::defaultMethods()
     * @covers \Chimera\Mapping\Validator
     * @covers \Chimera\Mapping\Routing\Endpoint
     */
    public function validateShouldNotRaiseExceptionsWhenAsyncNotIsProvided(): void
    {
        $annotation = new ExecuteEndpoint(self::ENDPOINT_DATA + ['command' => 'testing']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->command);
        self::assertSame(['PUT', 'PATCH', 'DELETE'], $annotation->methods);
        self::assertFalse($annotation->async);
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
     * @param array{command?: string, async?: bool} $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values, string $expectedMessage): void
    {
        $annotation = new ExecuteEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{command?: string, async?: bool}, 1: string}> */
    public function invalidScenarios(): iterable
    {
        yield 'missing command' => [
            [],
            '"command" of @Chimera\Mapping\Routing\ExecuteEndpoint declared on class A expects string.',
        ];
    }
}
