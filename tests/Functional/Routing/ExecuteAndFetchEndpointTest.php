<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\Routing;

use Chimera\Mapping\Reader;
use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Routing\ExecuteAndFetchEndpoint;
use Chimera\Mapping\Tests\Functional\TestCase;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;

#[PHPUnit\CoversClass(Endpoint::class)]
#[PHPUnit\CoversClass(ExecuteAndFetchEndpoint::class)]
#[PHPUnit\CoversClass(Reader::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class ExecuteAndFetchEndpointTest extends TestCase
{
    #[PHPUnit\Test]
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(ModifyAuthorHandler::class, ExecuteAndFetchEndpoint::class);

        self::assertInstanceOf(ExecuteAndFetchEndpoint::class, $annotation);
        self::assertSame('/authors/{id}', $annotation->path);
        self::assertSame(ModifyAuthor::class, $annotation->command);
        self::assertSame(FetchAuthor::class, $annotation->query);
        self::assertSame(['PUT'], $annotation->methods);
        self::assertSame('authors.modify', $annotation->name);
        self::assertNull($annotation->app);
    }

    #[PHPUnit\Test]
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(DisableAuthorHandler::class, ExecuteAndFetchEndpoint::class);

        self::assertInstanceOf(ExecuteAndFetchEndpoint::class, $annotation);
        self::assertSame('/authors/{id}', $annotation->path);
        self::assertSame(DisableAuthor::class, $annotation->command);
        self::assertSame(FetchAuthor::class, $annotation->query);
        self::assertSame(['PATCH'], $annotation->methods);
        self::assertSame('authors.disable', $annotation->name);
        self::assertSame('my-app', $annotation->app);
    }

    #[PHPUnit\Test]
    public function exceptionShouldBeRaisedWhenRequiredPropertiesAreMissing(): void
    {
        $this->expectException(AnnotationException::class);
        $this->readAnnotation(SomethingHandler::class, ExecuteAndFetchEndpoint::class);
    }
}

final class ModifyAuthor
{
}

final class DisableAuthor
{
}

final class FetchAuthor
{
}

/**
 * @ExecuteAndFetchEndpoint(
 *     "/authors/{id}",
 *     command=ModifyAuthor::class,
 *     query=FetchAuthor::class,
 *     name="authors.modify",
 *     methods={"PUT"}
 * )
 */
final class ModifyAuthorHandler
{
}

/**
 * @ExecuteAndFetchEndpoint(
 *     path="/authors/{id}",
 *     command=DisableAuthor::class,
 *     query=FetchAuthor::class,
 *     name="authors.disable",
 *     app="my-app",
 *     methods={"PATCH"}
 * )
 */
final class DisableAuthorHandler
{
}

/** @ExecuteAndFetchEndpoint */
final class SomethingHandler
{
}
