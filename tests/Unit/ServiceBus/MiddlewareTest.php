<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\ServiceBus;

use Chimera\Mapping\ServiceBus\Middleware;
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
        $annotation = new Middleware(['bus' => 'testing', 'priority' => 10]);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->bus);
        self::assertSame(10, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenValueAttributeIsUsed(): void
    {
        $annotation = new Middleware(['value' => 'testing', 'priority' => 10]);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->bus);
        self::assertSame(10, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function explicitlySetBusShouldBePickedInsteadOfValue(): void
    {
        $annotation = new Middleware(['value' => 'test', 'bus' => 'testing']);

        self::assertSame('testing', $annotation->bus);
    }

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenNothingIsProvided(): void
    {
        $annotation = new Middleware([]);
        $annotation->validate('class A');

        self::assertNull($annotation->bus);
        self::assertSame(0, $annotation->priority);
    }

    #[PHPUnit\Test]
    public function validateShouldRaiseExceptionWhenBusIsEmpty(): void
    {
        $annotation = new Middleware(['bus' => '     ']);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage(
            '"bus" of @Chimera\Mapping\ServiceBus\Middleware declared on class A expects string.',
        );

        $annotation->validate('class A');
    }
}
