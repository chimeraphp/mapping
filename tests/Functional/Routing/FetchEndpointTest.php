<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\Routing;

use Chimera\Mapping\Reader;
use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Routing\FetchEndpoint;
use Chimera\Mapping\Tests\Functional\TestCase;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;

#[PHPUnit\CoversClass(Endpoint::class)]
#[PHPUnit\CoversClass(FetchEndpoint::class)]
#[PHPUnit\CoversClass(Reader::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class FetchEndpointTest extends TestCase
{
    #[PHPUnit\Test]
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FetchBookHandler::class, FetchEndpoint::class);

        self::assertInstanceOf(FetchEndpoint::class, $annotation);
        self::assertSame('/books/{id}', $annotation->path);
        self::assertSame(FetchBook::class, $annotation->query);
        self::assertSame(['GET'], $annotation->methods);
        self::assertSame('books.fetch', $annotation->name);
        self::assertNull($annotation->app);
    }

    #[PHPUnit\Test]
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FindBooksHandler::class, FetchEndpoint::class);

        self::assertInstanceOf(FetchEndpoint::class, $annotation);
        self::assertSame('/books', $annotation->path);
        self::assertSame(FindBooks::class, $annotation->query);
        self::assertSame(['GET'], $annotation->methods);
        self::assertSame('books.find', $annotation->name);
        self::assertSame('my-app', $annotation->app);
    }

    #[PHPUnit\Test]
    public function exceptionShouldBeRaisedWhenRequiredPropertiesAreMissing(): void
    {
        $this->expectException(AnnotationException::class);
        $this->readAnnotation(FindAuthorsHandler::class, FetchEndpoint::class);
    }
}

final class FetchBook
{
}

final class FindBooks
{
}

/** @FetchEndpoint("/books/{id}", query=FetchBook::class, name="books.fetch") */
final class FetchBookHandler
{
}

/** @FetchEndpoint(path="/books", query=FindBooks::class, name="books.find", app="my-app") */
final class FindBooksHandler
{
}

/** @FetchEndpoint */
final class FindAuthorsHandler
{
}
