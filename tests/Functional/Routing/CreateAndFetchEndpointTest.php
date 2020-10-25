<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional\Routing;

use Chimera\Mapping\Routing\CreateAndFetchEndpoint;
use Chimera\Mapping\Tests\Functional\TestCase;
use Doctrine\Common\Annotations\AnnotationException;

use function assert;

/**
 * @covers \Chimera\Mapping\Routing\Endpoint
 * @covers \Chimera\Mapping\Routing\CreateAndFetchEndpoint
 * @covers \Chimera\Mapping\Reader
 * @covers \Chimera\Mapping\Validator
 */
final class CreateAndFetchEndpointTest extends TestCase
{
    /** @test */
    public function defaultValueShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(RegisterCustomerHandler::class, CreateAndFetchEndpoint::class);
        assert($annotation instanceof CreateAndFetchEndpoint || $annotation === null);

        self::assertInstanceOf(CreateAndFetchEndpoint::class, $annotation);
        self::assertSame('/customers/{id}', $annotation->path);
        self::assertSame(RegisterCustomer::class, $annotation->command);
        self::assertSame(FetchCustomer::class, $annotation->query);
        self::assertSame(['POST'], $annotation->methods);
        self::assertSame('customers.create', $annotation->name);
        self::assertSame('customers.fetch', $annotation->redirectTo);
        self::assertNull($annotation->app);
    }

    /** @test */
    public function propertiesShouldBeConfiguredProperly(): void
    {
        $annotation = $this->readAnnotation(RegisterLibrarianHandler::class, CreateAndFetchEndpoint::class);
        assert($annotation instanceof CreateAndFetchEndpoint || $annotation === null);

        self::assertInstanceOf(CreateAndFetchEndpoint::class, $annotation);
        self::assertSame('/librarians/{id}', $annotation->path);
        self::assertSame(RegisterLibrarian::class, $annotation->command);
        self::assertSame(FetchLibrarian::class, $annotation->query);
        self::assertSame(['POST'], $annotation->methods);
        self::assertSame('librarians.create', $annotation->name);
        self::assertSame('librarians.fetch', $annotation->redirectTo);
        self::assertSame('my-app', $annotation->app);
    }

    /** @test */
    public function exceptionShouldBeRaisedWhenRequiredPropertiesAreMissing(): void
    {
        $this->expectException(AnnotationException::class);
        $this->readAnnotation(RegisterSomethingElseHandler::class, CreateAndFetchEndpoint::class);
    }
}

final class RegisterCustomer
{
}

final class FetchCustomer
{
}

final class RegisterLibrarian
{
}

final class FetchLibrarian
{
}

/**
 * @CreateAndFetchEndpoint(
 *     "/customers/{id}",
 *     command=RegisterCustomer::class,
 *     query=FetchCustomer::class,
 *     name="customers.create",
 *     redirectTo="customers.fetch"
 * )
 */
final class RegisterCustomerHandler
{
}

/**
 * @CreateAndFetchEndpoint(
 *     path="/librarians/{id}",
 *     command=RegisterLibrarian::class,
 *     query=FetchLibrarian::class,
 *     name="librarians.create",
 *     app="my-app",
 *     redirectTo="librarians.fetch"
 * )
 */
final class RegisterLibrarianHandler
{
}

/** @CreateAndFetchEndpoint */
final class RegisterSomethingElseHandler
{
}
