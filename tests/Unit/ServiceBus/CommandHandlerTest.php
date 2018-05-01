<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Unit\ServiceBus;

use Doctrine\Common\Annotations\AnnotationException;
use Lcobucci\Chimera\Mapping\ServiceBus\CommandHandler;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Lcobucci\Chimera\Mapping\ServiceBus\CommandHandler
 */
final class CommandHandlerTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Lcobucci\Chimera\Mapping\Validator
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
     * @covers \Lcobucci\Chimera\Mapping\Validator
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
            'empty handles'      => [[]],
            'non-string handles' => [['handles' => false]],
            'non-string value'   => [['value' => false]],
        ];
    }
}
