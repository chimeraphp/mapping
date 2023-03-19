<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\ServiceBus;

use Chimera\Mapping\Reader;
use Chimera\Mapping\ServiceBus\CommandHandler;
use Chimera\Mapping\ServiceBus\Handler;
use Chimera\Mapping\Tests\Functional\TestCase;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;

#[PHPUnit\CoversClass(CommandHandler::class)]
#[PHPUnit\CoversClass(Handler::class)]
#[PHPUnit\CoversClass(Reader::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class CommandHandlerTest extends TestCase
{
    #[PHPUnit\Test]
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(CreateAuthorHandler::class, CommandHandler::class);

        self::assertInstanceOf(CommandHandler::class, $annotation);
        self::assertSame(CreateAuthor::class, $annotation->handles);
        self::assertSame('handle', $annotation->method);
    }

    #[PHPUnit\Test]
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(CreateBookHandler::class, CommandHandler::class);

        self::assertInstanceOf(CommandHandler::class, $annotation);
        self::assertSame(CreateBook::class, $annotation->handles);
        self::assertSame('handle', $annotation->method);
    }

    #[PHPUnit\Test]
    public function propertiesShouldBeConfiguredProperlyViaMethodsToo(): void
    {
        $annotation = $this->readAnnotation(BorrowBookHandler::class, CommandHandler::class);

        self::assertInstanceOf(CommandHandler::class, $annotation);
        self::assertSame(BorrowBook::class, $annotation->handles);
        self::assertSame('process', $annotation->method);
    }

    #[PHPUnit\Test]
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
