<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

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
        $annotation = new TestAnnotation(
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
        $annotation = new TestAnnotation(
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
     *
     * @covers ::__construct()
     */
    public function explicitlySetPathShouldBePickedInsteadOfValue(): void
    {
        $annotation = new TestAnnotation(
            ['value' => '/tests', 'path' => '/testing', 'name' => 'test', 'app' => 'my-app', 'methods' => ['GET']]
        );

        self::assertSame('/testing', $annotation->path);
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
     * @param array{path?: string, value?: string, name?: string, app?: string, methods?: mixed} $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values, string $expectedMessage): void
    {
        $annotation = new TestAnnotation($values);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{path?: string, value?: string, name?: string, app?: string, methods?: mixed}, 1: string}> */
    public function invalidScenarios(): iterable
    {
        yield 'null path' => [
            ['name' => 'test', 'methods' => ['POST']],
            '"path" of @Chimera\Mapping\Tests\Unit\Routing\TestAnnotation declared on class A expects string.',
        ];

        yield 'null name' => [
            ['path' => '/', 'methods' => ['POST']],
            '"name" of @Chimera\Mapping\Tests\Unit\Routing\TestAnnotation declared on class A expects string.',
        ];

        yield 'empty array methods' => [
            ['path' => '/', 'name' => 'test', 'methods' => []],
            '"methods" of @Chimera\Mapping\Tests\Unit\Routing\TestAnnotation declared on class A expects array.',
        ];

        yield 'non-string elements in methods' => [
            ['path' => '/', 'name' => 'test', 'methods' => [false]],
            '"methods" of @Chimera\Mapping\Tests\Unit\Routing\TestAnnotation declared on class A accepts '
            . 'only [GET, POST, DELETE, PATCH, PUT, OPTIONS, HEAD], but got .',
        ];

        yield 'non HTTP methods' => [
            ['path' => '/', 'name' => 'test', 'methods' => ['blah']],
            '"methods" of @Chimera\Mapping\Tests\Unit\Routing\TestAnnotation declared on class A accepts '
            . 'only [GET, POST, DELETE, PATCH, PUT, OPTIONS, HEAD], but got blah.',
        ];
    }
}
