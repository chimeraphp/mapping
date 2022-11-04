<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\Middleware;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;

#[PHPUnit\CoversClass(Middleware::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class MiddlewareTest extends TestCase
{
    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new Middleware(['path' => '/tests', 'app' => 'testing', 'priority' => 10]);
        $annotation->validate('class A');

        self::assertSame('/tests', $annotation->path);
        self::assertSame('testing', $annotation->app);
        self::assertSame(10, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function explicitlySetPathShouldBePickedInsteadOfValue(): void
    {
        $annotation = new Middleware(['value' => '/tests', 'path' => '/testing', 'app' => 'testing', 'priority' => 10]);

        self::assertSame('/testing', $annotation->path);
        self::assertSame('testing', $annotation->app);
        self::assertSame(10, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenValueAttributeIsUsed(): void
    {
        $annotation = new Middleware(['value' => '/tests', 'app' => 'testing', 'priority' => 10]);
        $annotation->validate('class A');

        self::assertSame('/tests', $annotation->path);
        self::assertSame('testing', $annotation->app);
        self::assertSame(10, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenNothingIsProvided(): void
    {
        $annotation = new Middleware([]);
        $annotation->validate('class A');

        self::assertSame('/', $annotation->path);
        self::assertNull($annotation->app);
        self::assertSame(0, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function validateShouldRaiseExceptionWhenAppIsEmpty(): void
    {
        $annotation = new Middleware(['app' => '     ']);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage(
            '"app" of @Chimera\Mapping\Routing\Middleware declared on class A expects string.',
        );

        $annotation->validate('class A');
    }
}
