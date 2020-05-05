<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\Endpoint;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Chimera\Mapping\Routing\Endpoint
 */
final class EndpointTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Chimera\Mapping\Validator
     */
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = $this->createInstance(
            ['path' => '/tests', 'name' => 'test', 'app' => 'my-app', 'methods' => ['GET']]
        );

        $annotation->validate('class A');

        self::assertSame('/tests', $annotation->path);
        self::assertSame('test', $annotation->name);
        self::assertSame('my-app', $annotation->app);
        self::assertSame(['GET'], $annotation->methods);
    }

    /**
     * @test
     * @dataProvider invalidScenarios
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Chimera\Mapping\Validator
     *
     * @param mixed[] $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values): void
    {
        $annotation = $this->createInstance($values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /**
     * @return mixed[][]
     */
    public function invalidScenarios(): array
    {
        return [
            'null path'                      => [['name' => 'test']],
            'null name'                      => [['path' => '/']],
            'empty array methods'            => [['path' => '/', 'name' => 'test', 'methods' => []]],
            'non-string elements in methods' => [['path' => '/', 'name' => 'test', 'methods' => [false]]],
            'non HTTP methods'               => [['path' => '/', 'name' => 'test', 'methods' => ['blah']]],
        ];
    }

    /**
     * @param mixed[] $values
     */
    private function createInstance(array $values): Endpoint
    {
        $annotation = $this->getMockForAbstractClass(Endpoint::class, [$values]);

        $annotation->method('defaultMethods')
                   ->willReturn(['GET']);

        return $annotation;
    }
}
