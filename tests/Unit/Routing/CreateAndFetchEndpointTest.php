<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\CreateAndFetchEndpoint;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Chimera\Mapping\Routing\CreateAndFetchEndpoint
 */
final class CreateAndFetchEndpointTest extends TestCase
{
    private const ENDPOINT_DATA = ['path' => '/tests', 'name' => 'test'];

    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::validateAdditionalData()
     * @covers ::defaultMethods()
     * @covers \Chimera\Mapping\Validator
     * @covers \Chimera\Mapping\Routing\Endpoint
     */
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new CreateAndFetchEndpoint(
            self::ENDPOINT_DATA + ['command' => 'test1', 'query' => 'test2', 'redirectTo' => 'test3']
        );

        $annotation->validate('class A');

        self::assertSame('test1', $annotation->command);
        self::assertSame('test2', $annotation->query);
        self::assertSame('test3', $annotation->redirectTo);
        self::assertSame(['POST'], $annotation->methods);
    }

    /**
     * @test
     * @dataProvider invalidScenarios
     *
     * @covers ::__construct()
     * @covers ::validateAdditionalData()
     * @covers ::defaultMethods()
     * @covers \Chimera\Mapping\Validator
     * @covers \Chimera\Mapping\Routing\Endpoint
     *
     * @param mixed[] $values
     */
    public function validateShouldRaiseExceptionWhenInvalidDataWasProvided(array $values): void
    {
        $annotation = new CreateAndFetchEndpoint(self::ENDPOINT_DATA + $values);

        $this->expectException(AnnotationException::class);
        $annotation->validate('class A');
    }

    /**
     * @return mixed[][]
     */
    public function invalidScenarios(): array
    {
        return [
            'empty command'      => [['query' => 'test', 'redirectTo' => 'test3']],
            'non-string command' => [['command' => false, 'query' => 'test', 'redirectTo' => 'test3']],
            'empty query'        => [['command' => 'test', 'redirectTo' => 'test3']],
            'non-string query'   => [['query' => false, 'command' => 'test', 'redirectTo' => 'test3']],
            'empty redirectTo'      => [['command' => 'test', 'query' => 'test']],
            'non-string redirectTo' => [['command' => 'test', 'query' => 'test', 'redirectTo' => false]],
        ];
    }
}
