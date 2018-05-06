<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\ServiceBus;

use Chimera\Mapping\ServiceBus\CommandHandler;
use Chimera\Mapping\Tests\Functional\TestCase;
use Doctrine\Common\Annotations\AnnotationException;
use function assert;

final class CommandHandlerTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Chimera\Mapping\ServiceBus\CommandHandler
     * @covers \Chimera\Mapping\Reader
     */
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(CreateAuthorHandler::class, CommandHandler::class);
        assert($annotation instanceof CommandHandler || $annotation === null);

        self::assertInstanceOf(CommandHandler::class, $annotation);
        self::assertSame(CreateAuthor::class, $annotation->handles);
    }

    /**
     * @test
     *
     * @covers \Chimera\Mapping\ServiceBus\CommandHandler
     * @covers \Chimera\Mapping\Reader
     */
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(CreateBookHandler::class, CommandHandler::class);
        assert($annotation instanceof CommandHandler || $annotation === null);

        self::assertInstanceOf(CommandHandler::class, $annotation);
        self::assertSame(CreateBook::class, $annotation->handles);
    }

    /**
     * @test
     *
     * @covers \Chimera\Mapping\ServiceBus\CommandHandler
     * @covers \Chimera\Mapping\Reader
     */
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

/**
 * @CommandHandler(CreateAuthor::class)
 */
final class CreateAuthorHandler
{
}

/**
 * @CommandHandler(handles=CreateBook::class)
 */
final class CreateBookHandler
{
}

/**
 * @CommandHandler
 */
final class ModifyAuthorHandler
{
}
