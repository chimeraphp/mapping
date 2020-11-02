<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\ServiceBus;

use Chimera\Mapping\ServiceBus\Middleware;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \Chimera\Mapping\ServiceBus\Middleware */
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
        $annotation = new Middleware(['bus' => 'testing', 'priority' => 10]);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->bus);
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
        $annotation = new Middleware(['value' => 'testing', 'priority' => 10]);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->bus);
        self::assertSame(10, $annotation->priority);
    }

    /**
     * @test
     *
     * @covers ::__construct()
     */
    public function explicitlySetBusShouldBePickedInsteadOfValue(): void
    {
        $annotation = new Middleware(['value' => 'test', 'bus' => 'testing']);

        self::assertSame('testing', $annotation->bus);
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

        self::assertNull($annotation->bus);
        self::assertSame(0, $annotation->priority);
    }
}
