<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\Routing;

use Chimera\Mapping\Reader;
use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Routing\SimpleEndpoint;
use Chimera\Mapping\Tests\Functional\TestCase;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;

#[PHPUnit\CoversClass(Endpoint::class)]
#[PHPUnit\CoversClass(SimpleEndpoint::class)]
#[PHPUnit\CoversClass(Reader::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class SimpleEndpointTest extends TestCase
{
    #[PHPUnit\Test]
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FetchBookRequestHandler::class, SimpleEndpoint::class);

        self::assertInstanceOf(SimpleEndpoint::class, $annotation);
        self::assertSame('/books/{id}', $annotation->path);
        self::assertSame(['GET'], $annotation->methods);
        self::assertSame('books.fetch', $annotation->name);
        self::assertNull($annotation->app);
    }

    #[PHPUnit\Test]
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FindBooksRequestHandler::class, SimpleEndpoint::class);

        self::assertInstanceOf(SimpleEndpoint::class, $annotation);
        self::assertSame('/books', $annotation->path);
        self::assertSame(['GET'], $annotation->methods);
        self::assertSame('books.find', $annotation->name);
        self::assertSame('my-app', $annotation->app);
    }

    #[PHPUnit\Test]
    public function exceptionShouldBeRaisedWhenRequiredPropertiesAreMissing(): void
    {
        $this->expectException(AnnotationException::class);
        $this->readAnnotation(FindAuthorsRequestHandler::class, SimpleEndpoint::class);
    }
}

/** @SimpleEndpoint("/books/{id}", name="books.fetch") */
final class FetchBookRequestHandler
{
}

/** @SimpleEndpoint(path="/books", name="books.find", app="my-app") */
final class FindBooksRequestHandler
{
}

/** @SimpleEndpoint */
final class FindAuthorsRequestHandler
{
}
