<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \Chimera\Mapping\Routing\Endpoint */
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
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Chimera\Mapping\Validator
     */
    public function validateShouldNotRaiseExceptionsWhenValueAttributeIsUsed(): void
    {
        $annotation = $this->createInstance(
            ['value' => '/tests', 'name' => 'test', 'app' => 'my-app', 'methods' => ['GET']]
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
     * @param array{path?: string, value?: string, name?: string, app?: string, methods?: mixed[]} $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values): void
    {
        $annotation = $this->createInstance($values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{path?: string, value?: string, name?: string, app?: string, methods?: mixed[]}}> */
    public function invalidScenarios(): iterable
    {
        yield 'null path' => [['name' => 'test', 'methods' => ['POST']]];
        yield 'null name' => [['path' => '/', 'methods' => ['POST']]];
        yield 'empty array methods' => [['path' => '/', 'name' => 'test', 'methods' => []]];
        yield 'non-string elements in methods' => [['path' => '/', 'name' => 'test', 'methods' => [false]]];
        yield 'non HTTP methods' => [['path' => '/', 'name' => 'test', 'methods' => ['blah']]];
    }

    /** @param array{path?: string, value?: string, name?: string, app?: string, methods?: mixed[]} $values */
    private function createInstance(array $values): Endpoint
    {
        return new class ($values) extends Endpoint
        {
            // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
            protected function validateAdditionalData(Validator $validator): void
            {
            }

            /** @inheritdoc */
            protected function defaultMethods(): array
            {
                return ['GET'];
            }
        };
    }
}
