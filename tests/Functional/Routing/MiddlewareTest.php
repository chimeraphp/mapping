<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\Routing;

use Chimera\Mapping\Reader;
use Chimera\Mapping\Routing\Middleware;
use Chimera\Mapping\Tests\Functional\TestCase;
use Chimera\Mapping\Validator;
use PHPUnit\Framework\Attributes as PHPUnit;

#[PHPUnit\CoversClass(Middleware::class)]
#[PHPUnit\CoversClass(Reader::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class MiddlewareTest extends TestCase
{
    #[PHPUnit\Test]
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(HttpMiddleware1::class, Middleware::class);

        self::assertInstanceOf(Middleware::class, $annotation);
        self::assertSame('/testing', $annotation->path);
        self::assertSame('my-app', $annotation->app);
        self::assertSame(1, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(HttpMiddleware2::class, Middleware::class);

        self::assertInstanceOf(Middleware::class, $annotation);
        self::assertSame('/testing', $annotation->path);
        self::assertSame('my-app', $annotation->app);
        self::assertSame(1, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function everythingShouldBeFineIfNoValueWasProvided(): void
    {
        $annotation = $this->readAnnotation(HttpMiddleware3::class, Middleware::class);

        self::assertInstanceOf(Middleware::class, $annotation);
        self::assertSame('/', $annotation->path);
        self::assertNull($annotation->app);
        self::assertSame(0, $annotation->priority);
    }
}

/** @Middleware("/testing", priority=1, app="my-app") */
final class HttpMiddleware1
{
}

/** @Middleware(path="/testing", priority=1, app="my-app") */
final class HttpMiddleware2
{
}

/** @Middleware */
final class HttpMiddleware3
{
}
