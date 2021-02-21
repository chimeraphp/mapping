<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\ServiceBus;

use Chimera\Mapping\ServiceBus\QueryHandler;
use Chimera\Mapping\Tests\Functional\TestCase;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * @covers \Chimera\Mapping\ServiceBus\QueryHandler
 * @covers \Chimera\Mapping\ServiceBus\Handler
 * @covers \Chimera\Mapping\Reader
 * @covers \Chimera\Mapping\Validator
 */
final class QueryHandlerTest extends TestCase
{
    /** @test */
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FetchBookHandler::class, QueryHandler::class);

        self::assertInstanceOf(QueryHandler::class, $annotation);
        self::assertSame(FetchBook::class, $annotation->handles);
        self::assertSame('handle', $annotation->method);
    }

    /** @test */
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FindBooksHandler::class, QueryHandler::class);

        self::assertInstanceOf(QueryHandler::class, $annotation);
        self::assertSame(FindBooks::class, $annotation->handles);
        self::assertSame('handle', $annotation->method);
    }

    /** @test */
    public function propertiesShouldBeConfiguredProperlyViaMethodsToo(): void
    {
        $annotation = $this->readAnnotation(FindAuthorsHandler::class, QueryHandler::class);

        self::assertInstanceOf(QueryHandler::class, $annotation);
        self::assertSame(FindAuthors::class, $annotation->handles);
        self::assertSame('process', $annotation->method);
    }

    /** @test */
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

final class FindAuthors
{
}

final class FindAuthorsHandler
{
    /** @QueryHandler */
    public function process(FindAuthors $query): void // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    {
    }
}
