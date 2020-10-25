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
     * @param mixed[] $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values): void
    {
        $annotation = new ExecuteEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /** @return mixed[][] */
    public function invalidScenarios(): array
    {
        return [
            'empty command'      => [[]],
        ];
    }
}
