<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\ServiceBus;

use Chimera\Mapping\ServiceBus\CommandHandler;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Chimera\Mapping\ServiceBus\CommandHandler
 */
final class CommandHandlerTest extends TestCase
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
        $annotation = new CommandHandler(['handles' => 'testing']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->handles);
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
        $annotation = new CommandHandler($values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /**
     * @return mixed[][]
     */
    public function invalidScenarios(): array
    {
        return [
            'empty handles' => [[]],
        ];
    }
}
