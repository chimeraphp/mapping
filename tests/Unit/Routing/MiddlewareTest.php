<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\Middleware;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Chimera\Mapping\Routing\Middleware
 */
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
}
