<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit\Routing;

use Chimera\Mapping\Routing\Endpoint;
use Chimera\Mapping\Validator;

final class TestAnnotation extends Endpoint
{
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    protected function validateAdditionalData(Validator $validator): void
    {
    }

    /** @inheritdoc */
    protected function defaultMethods(): array
    {
        return ['GET'];
    }
}
