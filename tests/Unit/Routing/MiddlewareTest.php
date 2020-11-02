<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\Middleware;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \Chimera\Mapping\Routing\Middleware */
final class MiddlewareTest extends TestCase
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
        $annotation = new Middleware(['path' => '/tests', 'app' => 'testing', 'priority' => 10]);
        $annotation->validate('class A');

        self::assertSame('/tests', $annotation->path);
        self::assertSame('testing', $annotation->app);
        self::assertSame(10, $annotation->priority);
    }

    /**
     * @test
     *
     * @covers ::__construct()
     */
    public function explicitlySetPathShouldBePickedInsteadOfValue(): void
    {
        $annotation = new Middleware(['value' => '/tests', 'path' => '/testing', 'app' => 'testing', 'priority' => 10]);

        self::assertSame('/testing', $annotation->path);
        self::assertSame('testing', $annotation->app);
        self::assertSame(10, $annotation->priority);
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
        $annotation = new Middleware(['value' => '/tests', 'app' => 'testing', 'priority' => 10]);
        $annotation->validate('class A');

        self::assertSame('/tests', $annotation->path);
        self::assertSame('testing', $annotation->app);
        self::assertSame(10, $annotation->priority);
    }

    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Chimera\Mapping\Validator
     */
    public function validateShouldNotRaiseExceptionsWhenNothingIsProvided(): void
    {
        $annotation = new Middleware([]);
        $annotation->validate('class A');

        self::assertSame('/', $annotation->path);
        self::assertNull($annotation->app);
        self::assertSame(0, $annotation->priority);
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::validate
     * @covers \Chimera\Mapping\Validator
     */
    public function validateShouldRaiseExceptionWhenAppIsEmpty(): void
    {
        $annotation = new Middleware(['app' => '     ']);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage(
            '"app" of @Chimera\Mapping\Routing\Middleware declared on class A expects string.'
        );

        $annotation->validate('class A');
    }
}
