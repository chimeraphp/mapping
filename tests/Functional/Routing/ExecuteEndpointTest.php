<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\Routing;

use Chimera\Mapping\Reader;
use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Routing\ExecuteEndpoint;
use Chimera\Mapping\Tests\Functional\TestCase;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;

#[PHPUnit\CoversClass(Endpoint::class)]
#[PHPUnit\CoversClass(ExecuteEndpoint::class)]
#[PHPUnit\CoversClass(Reader::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class ExecuteEndpointTest extends TestCase
{
    #[PHPUnit\Test]
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(RemoveBookHandler::class, ExecuteEndpoint::class);

        self::assertInstanceOf(ExecuteEndpoint::class, $annotation);
        self::assertSame('/books/{id}', $annotation->path);
        self::assertSame(RemoveBook::class, $annotation->command);
        self::assertSame(['DELETE'], $annotation->methods);
        self::assertSame('books.remove', $annotation->name);
        self::assertFalse($annotation->async);
        self::assertNull($annotation->app);
    }

    #[PHPUnit\Test]
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(RenameBookHandler::class, ExecuteEndpoint::class);

        self::assertInstanceOf(ExecuteEndpoint::class, $annotation);
        self::assertSame('/books/{id}', $annotation->path);
        self::assertSame(RenameBook::class, $annotation->command);
        self::assertSame(['PATCH'], $annotation->methods);
        self::assertSame('books.rename', $annotation->name);
        self::assertTrue($annotation->async);
        self::assertSame('my-app', $annotation->app);
    }

    #[PHPUnit\Test]
    public function exceptionShouldBeRaisedWhenRequiredPropertiesAreMissing(): void
    {
        $this->expectException(AnnotationException::class);
        $this->readAnnotation(RemoveAuthorHandler::class, ExecuteEndpoint::class);
    }
}

final class RemoveBook
{
}

final class RenameBook
{
}

/** @ExecuteEndpoint("/books/{id}", command=RemoveBook::class, name="books.remove", methods={"DELETE"}) */
final class RemoveBookHandler
{
}

/**
 * @ExecuteEndpoint(
 *     path="/books/{id}",
 *     command=RenameBook::class,
 *     name="books.rename",
 *     app="my-app",
 *     async=true,
 *     methods={"PATCH"}
 * )
 */
final class RenameBookHandler
{
}

/** @ExecuteEndpoint */
final class RemoveAuthorHandler
{
}
