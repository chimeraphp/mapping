<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Routing\SimpleEndpoint;
use Chimera\Mapping\Validator;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;

#[PHPUnit\CoversClass(SimpleEndpoint::class)]
#[PHPUnit\CoversClass(Validator::class)]
#[PHPUnit\CoversClass(Endpoint::class)]
final class SimpleEndpointTest extends TestCase
{
    private const ENDPOINT_DATA = ['path' => '/tests', 'name' => 'test'];

    #[PHPUnit\Test]
    public function validateShouldNotRaiseExceptionsWhenStateIsValid(): void
    {
        $annotation = new SimpleEndpoint(self::ENDPOINT_DATA);
        $annotation->validate('class A');

        self::assertSame(['GET'], $annotation->methods);
    }
}
