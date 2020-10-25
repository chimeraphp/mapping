<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\ServiceBus;

use Chimera\Mapping\ServiceBus\QueryHandler;
use Chimera\Mapping\Tests\Functional\TestCase;
use Doctrine\Common\Annotations\AnnotationException;

use function assert;

final class QueryHandlerTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Chimera\Mapping\ServiceBus\QueryHandler
     * @covers \Chimera\Mapping\Reader
     */
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FetchBookHandler::class, QueryHandler::class);
        assert($annotation instanceof QueryHandler || $annotation === null);

        self::assertInstanceOf(QueryHandler::class, $annotation);
        self::assertSame(FetchBook::class, $annotation->handles);
    }

    /**
     * @test
     *
     * @covers \Chimera\Mapping\ServiceBus\QueryHandler
     * @covers \Chimera\Mapping\Reader
     */
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FindBooksHandler::class, QueryHandler::class);
        assert($annotation instanceof QueryHandler || $annotation === null);

        self::assertInstanceOf(QueryHandler::class, $annotation);
        self::assertSame(FindBooks::class, $annotation->handles);
    }

    /**
     * @test
     *
     * @covers \Chimera\Mapping\ServiceBus\QueryHandler
     * @covers \Chimera\Mapping\Reader
     */
    public function exceptionShouldBeRaisedWhenRequiredPropertiesAreMissing(): void
    {
        $this->expectException(AnnotationException::class);
        $this->readAnnotation(FetchAuthorHandler::class, QueryHandler::class);
    }
}


final class FetchBook
{
}

final class FindBooks
{
}

/** @QueryHandler(FetchBook::class) */
final class FetchBookHandler
{
}

/** @QueryHandler(handles=FindBooks::class) */
final class FindBooksHandler
{
}

/** @QueryHandler */
final class FetchAuthorHandler
{
}
