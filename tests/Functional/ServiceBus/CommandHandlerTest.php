<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\ServiceBus;

use Chimera\Mapping\ServiceBus\CommandHandler;
use Chimera\Mapping\Tests\Functional\TestCase;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * @covers \Chimera\Mapping\ServiceBus\CommandHandler
 * @covers \Chimera\Mapping\ServiceBus\Handler
 * @covers \Chimera\Mapping\Reader
 * @covers \Chimera\Mapping\Validator
 */
final class CommandHandlerTest extends TestCase
{
    /** @test */
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(CreateAuthorHandler::class, CommandHandler::class);

        self::assertInstanceOf(CommandHandler::class, $annotation);
        self::assertSame(CreateAuthor::class, $annotation->handles);
        self::assertSame('handle', $annotation->method);
    }

    /** @test */
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(CreateBookHandler::class, CommandHandler::class);

        self::assertInstanceOf(CommandHandler::class, $annotation);
        self::assertSame(CreateBook::class, $annotation->handles);
        self::assertSame('handle', $annotation->method);
    }

    /** @test */
    public function propertiesShouldBeConfiguredProperlyViaMethodsToo(): void
    {
        $annotation = $this->readAnnotation(BorrowBookHandler::class, CommandHandler::class);

        self::assertInstanceOf(CommandHandler::class, $annotation);
        self::assertSame(BorrowBook::class, $annotation->handles);
        self::assertSame('process', $annotation->method);
    }

    /** @test */
    public function exceptionShouldBeRaisedWhenRequiredPropertiesAreMissing(): void
    {
        $this->expectException(AnnotationException::class);
        $this->readAnnotation(ModifyAuthorHandler::class, CommandHandler::class);
    }
}

final class CreateAuthor
{
}

final class CreateBook
{
}

/** @CommandHandler(CreateAuthor::class) */
final class CreateAuthorHandler
{
}

/** @CommandHandler(handles=CreateBook::class) */
final class CreateBookHandler
{
}

/** @CommandHandler */
final class ModifyAuthorHandler
{
}

final class BorrowBook
{
}

final class BorrowBookHandler
{
    /** @CommandHandler */
    public function process(BorrowBook $command): void // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    {
    }
}
