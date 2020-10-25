<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\Routing;

use Chimera\Mapping\Routing\SimpleEndpoint;
use Chimera\Mapping\Tests\Functional\TestCase;
use Doctrine\Common\Annotations\AnnotationException;

use function assert;

/**
 * @covers \Chimera\Mapping\Routing\Endpoint
 * @covers \Chimera\Mapping\Routing\SimpleEndpoint
 * @covers \Chimera\Mapping\Reader
 * @covers \Chimera\Mapping\Validator
 */
final class SimpleEndpointTest extends TestCase
{
    /** @test */
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FetchBookRequestHandler::class, SimpleEndpoint::class);
        assert($annotation instanceof SimpleEndpoint || $annotation === null);

        self::assertInstanceOf(SimpleEndpoint::class, $annotation);
        self::assertSame('/books/{id}', $annotation->path);
        self::assertSame(['GET'], $annotation->methods);
        self::assertSame('books.fetch', $annotation->name);
        self::assertNull($annotation->app);
    }

    /** @test */
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(FindBooksRequestHandler::class, SimpleEndpoint::class);
        assert($annotation instanceof SimpleEndpoint || $annotation === null);

        self::assertInstanceOf(SimpleEndpoint::class, $annotation);
        self::assertSame('/books', $annotation->path);
        self::assertSame(['GET'], $annotation->methods);
        self::assertSame('books.find', $annotation->name);
        self::assertSame('my-app', $annotation->app);
    }

    /** @test */
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
