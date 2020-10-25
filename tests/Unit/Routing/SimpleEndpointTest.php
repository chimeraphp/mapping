<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\SimpleEndpoint;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \Chimera\Mapping\Routing\SimpleEndpoint */
final class SimpleEndpointTest extends TestCase
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
        $annotation = new SimpleEndpoint(self::ENDPOINT_DATA);
        $annotation->validate('class A');

        self::assertSame(['GET'], $annotation->methods);
    }
}
