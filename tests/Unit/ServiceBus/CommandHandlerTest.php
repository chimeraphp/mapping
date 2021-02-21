<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\ServiceBus;

use Chimera\Mapping\ServiceBus\CommandHandler;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/** @coversDefaultClass \Chimera\Mapping\ServiceBus\CommandHandler */
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
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Chimera\Mapping\Validator
     */
    public function validateShouldNotRaiseExceptionsWhenValueAttributeIsUsed(): void
    {
        $annotation = new CommandHandler(['value' => 'testing']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->handles);
    }

    /**
     * @test
     *
     * @covers ::__construct()
     */
    public function explicitlySetHandlesShouldBePickedInsteadOfValue(): void
    {
        $annotation = new CommandHandler(['value' => 'test', 'handles' => 'testing']);

        self::assertSame('testing', $annotation->handles);
    }

    /**
     * @test
     *
     * @covers ::__construct()
     */
    public function explicitlySetMethodShouldBePickedInsteadOfValue(): void
    {
        $annotation = new CommandHandler(['method' => 'testing']);

        self::assertSame('testing', $annotation->method);
    }

    /**
     * @test
     * @dataProvider invalidScenarios
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Chimera\Mapping\Validator
     *
     * @param array{handles?: string} $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values, string $expectedMessage): void
    {
        $annotation = new CommandHandler($values);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{handles?: string}, 1: string}> */
    public function invalidScenarios(): iterable
    {
        yield 'missing handles' => [
            [],
            '"handles" of @Chimera\Mapping\ServiceBus\CommandHandler declared on class A expects string.',
        ];

        yield 'empty handles' => [
            ['handles' => ''],
            '"handles" of @Chimera\Mapping\ServiceBus\CommandHandler declared on class A expects string.',
        ];

        yield 'empty method' => [
            ['handles' => 'Test', 'method' => ''],
            '"method" of @Chimera\Mapping\ServiceBus\CommandHandler declared on class A expects string.',
        ];
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::configure
     */
    public function configureShouldModifyAttributesBasedOnReflectionData(): void
    {
        $annotation = new CommandHandler(['handles' => 'Test']);
        $annotation->configure(new ReflectionMethod(ExamplesForCommandHandlerViaReflection::class, 'process'));

        self::assertSame(ExamplesForCommandHandler::class, $annotation->handles);
        self::assertSame('process', $annotation->method);
    }

    /**
     * @test
     * @dataProvider invalidHandlerMethods
     *
     * @covers ::__construct
     * @covers ::configure
     */
    public function configureShouldThrowErrorInCaseOfInvalidParameterConfiguration(string $method): void
    {
        $annotation = new CommandHandler(['handles' => 'Test']);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage('The first parameter of the handler method must be a custom class');
        $annotation->configure(new ReflectionMethod(ExamplesForCommandHandlerViaReflection::class, $method));
    }

    /** @return iterable<string, array{0: string}> */
    public function invalidHandlerMethods(): iterable
    {
        yield 'no parameter' => ['noParameter'];
        yield 'no type' => ['noType'];
        yield 'using primitive type' => ['primitiveType'];
    }
}

final class ExamplesForCommandHandler
{
}

final class ExamplesForCommandHandlerViaReflection
{
    public function noParameter(): void
    {
    }

    // @phpstan-ignore-next-line
    public function noType($test): void // phpcs:ignore
    {
    }

    public function primitiveType(int $test): void // phpcs:ignore
    {
    }

    public function process(ExamplesForCommandHandler $command): void // phpcs:ignore
    {
    }
}
