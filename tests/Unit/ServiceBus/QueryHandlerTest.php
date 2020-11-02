<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\ServiceBus;

use Chimera\Mapping\ServiceBus\QueryHandler;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \Chimera\Mapping\ServiceBus\QueryHandler */
final class QueryHandlerTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Chimera\Mapping\Validator
     */
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new QueryHandler(['handles' => 'testing']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->handles);
    }

    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Chimera\Mapping\Validator
     */
    public function validateShouldNotRaiseExceptionsWhenValueAttributeIsUsed(): void
    {
        $annotation = new QueryHandler(['value' => 'testing']);
        $annotation->validate('class A');

        self::assertSame('testing', $annotation->handles);
    }

    /**
     * @test
     * @dataProvider invalidScenarios
     *
     * @covers ::__construct()
     * @covers ::validate()
     * @covers \Chimera\Mapping\Validator
     *
     * @param array{handles?: string} $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values): void
    {
        $annotation = new QueryHandler($values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /** @return iterable<string, array{0: array{handles?: string}}> */
    public function invalidScenarios(): iterable
    {
        yield 'empty handles' => [[]];
    }
}
