<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\ServiceBus;

use Chimera\Mapping\Reader;
use Chimera\Mapping\ServiceBus\Middleware;
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
        $annotation = $this->readAnnotation(BusMiddleware1::class, Middleware::class);

        self::assertInstanceOf(Middleware::class, $annotation);
        self::assertSame('my-app.query_bus', $annotation->bus);
        self::assertSame(1, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(BusMiddleware2::class, Middleware::class);

        self::assertInstanceOf(Middleware::class, $annotation);
        self::assertSame('my-app.query_bus', $annotation->bus);
        self::assertSame(1, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function everythingShouldBeFineIfNoValueWasProvided(): void
    {
        $annotation = $this->readAnnotation(BusMiddleware3::class, Middleware::class);

        self::assertInstanceOf(Middleware::class, $annotation);
        self::assertNull($annotation->bus);
        self::assertSame(0, $annotation->priority);
    }
}

/** @Middleware("my-app.query_bus", priority=1) */
final class BusMiddleware1
{
}

/** @Middleware(bus="my-app.query_bus", priority=1) */
final class BusMiddleware2
{
}

/** @Middleware */
final class BusMiddleware3
{
}
