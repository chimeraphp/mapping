<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\ServiceBus;

use Chimera\Mapping\ServiceBus\Middleware;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Chimera\Mapping\ServiceBus\Middleware
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
        $annotation = new Middleware(['bus' => 'testing', 'priority' => 10]);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->bus);
        self::assertSame(10, $annotation->priority);
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
        $annotation = new Middleware($values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /**
     * @return mixed[][]
     */
    public function invalidScenarios(): array
    {
        return [
            'non-string bus'   => [['bus' => false]],
            'non-int priority' => [['priority' => false]],
        ];
    }
}
