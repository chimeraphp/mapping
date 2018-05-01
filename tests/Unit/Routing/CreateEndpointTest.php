<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Unit\Routing;

use Doctrine\Common\Annotations\AnnotationException;
use Lcobucci\Chimera\Mapping\Routing\CreateEndpoint;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Lcobucci\Chimera\Mapping\Routing\CreateEndpoint
 */
final class CreateEndpointTest extends TestCase
{
    private const ENDPOINT_DATA = ['path' => '/tests', 'name' => 'test'];

    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validateAdditionalData()
     * @covers ::defaultMethods()
     * @covers \Lcobucci\Chimera\Mapping\Validator
     * @covers \Lcobucci\Chimera\Mapping\Routing\Endpoint
     */
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new CreateEndpoint(self::ENDPOINT_DATA + ['command' => 'testing', 'redirectTo' => 'test']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->command);
        self::assertSame('test', $annotation->redirectTo);
        self::assertSame(['POST'], $annotation->methods);
    }

    /**
     * @test
     * @dataProvider invalidScenarios
     *
     * @covers ::__construct()
     * @covers ::validateAdditionalData()
     * @covers ::defaultMethods()
     * @covers \Lcobucci\Chimera\Mapping\Validator
     * @covers \Lcobucci\Chimera\Mapping\Routing\Endpoint
     *
     * @param mixed[] $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values): void
    {
        $annotation = new CreateEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /**
     * @return mixed[][]
     */
    public function invalidScenarios(): array
    {
        return [
            'empty command'         => [['redirectTo' => 'test']],
            'non-string command'    => [['command' => false, 'redirectTo' => 'test']],
            'empty redirectTo'      => [['command' => 'test']],
            'non-string redirectTo' => [['command' => 'test', 'redirectTo' => false]],
        ];
    }
}
