<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\ServiceBus;

use Chimera\Mapping\ServiceBus\Handler;
use Chimera\Mapping\ServiceBus\QueryHandler;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

#[PHPUnit\CoversClass(QueryHandler::class)]
#[PHPUnit\CoversClass(Handler::class)]
#[PHPUnit\CoversClass(Validator::class)]
final class QueryHandlerTest extends TestCase
{
    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new QueryHandler(['handles' => 'testing']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->handles);
    }

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenValueAttributeIsUsed(): void
    {
        $annotation = new QueryHandler(['value' => 'testing']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->handles);
    }

    #[PHPUnit\Test]
    public function explicitlySetHandlesShouldBePickedInsteadOfValue(): void
    {
        $annotation = new QueryHandler(['value' => 'test', 'handles' => 'testing']);

        self::assertSame('testing', $annotation->handles);
    }

    #[PHPUnit\Test]
    public function explicitlySetMethodShouldBePickedInsteadOfValue(): void
    {
        $annotation = new QueryHandler(['method' => 'testing']);

        self::assertSame('testing', $annotation->method);
    }

    /** @param array{handles?: string} $values  */
    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('invalidScenarios')]
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values, string $expectedMessage): void
    {
        $annotation = new QueryHandler($values);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage($expectedMessage);

        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{handles?: string}, 1: string}> */
    public static function invalidScenarios(): iterable
    {
        yield 'missing handles' => [
            [],
            '"handles" of @Chimera\Mapping\ServiceBus\QueryHandler declared on class A expects string.',
        ];

        yield 'empty handles' => [
            ['handles' => ''],
            '"handles" of @Chimera\Mapping\ServiceBus\QueryHandler declared on class A expects string.',
        ];

        yield 'empty method' => [
            ['handles' => 'Test', 'method' => ''],
            '"method" of @Chimera\Mapping\ServiceBus\QueryHandler declared on class A expects string.',
        ];
    }

    #[PHPUnit\Test]
    public function configureShouldModifyAttributesBasedOnReflectionData(): void
    {
        $annotation = new QueryHandler(['handles' => 'Test']);
        $annotation->configure(new ReflectionMethod(ExamplesForQueryHandlerViaReflection::class, 'process'));

        self::assertSame(ExamplesForQueryHandler::class, $annotation->handles);
        self::assertSame('process', $annotation->method);
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('invalidHandlerMethods')]
    public function configureShouldThrowErrorInCaseOfInvalidParameterConfiguration(string $method): void
    {
        $annotation = new QueryHandler(['handles' => 'Test']);

        $this->expectException(AnnotationException::class);
        $this->expectExceptionMessage('The first parameter of the handler method must be a custom class');
        $annotation->configure(new ReflectionMethod(ExamplesForQueryHandlerViaReflection::class, $method));
    }

    /** @return iterable<string, array{0: string}> */
    public static function invalidHandlerMethods(): iterable
    {
        yield 'no parameter' => ['noParameter'];
        yield 'no type' => ['noType'];
        yield 'using primitive type' => ['primitiveType'];
    }
}

final class ExamplesForQueryHandler
{
}

final class ExamplesForQueryHandlerViaReflection
{
    public function noParameter(): void
    {
    }

    // @phpstan-ignore-next-line
    public function noType($test): void // phpcs:ignore
    {
    }

    public function primitiveType(int $test): void // phpcs:ignore
    {
    }

    public function process(ExamplesForQueryHandler $command): void // phpcs:ignore
    {
    }
}
