<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Functional\ServiceBus;

use Doctrine\Common\Annotations\AnnotationException;
use Lcobucci\Chimera\Mapping\ServiceBus\CommandHandler;
use Lcobucci\Chimera\Mapping\Tests\Functional\TestCase;
use function assert;

final class CommandHandlerTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Lcobucci\Chimera\Mapping\ServiceBus\CommandHandler
     * @covers \Lcobucci\Chimera\Mapping\Reader
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
     * @covers \Lcobucci\Chimera\Mapping\ServiceBus\CommandHandler
     * @covers \Lcobucci\Chimera\Mapping\Reader
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
     * @covers \Lcobucci\Chimera\Mapping\ServiceBus\CommandHandler
     * @covers \Lcobucci\Chimera\Mapping\Reader
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
