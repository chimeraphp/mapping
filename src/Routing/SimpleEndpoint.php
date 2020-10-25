<?php
declare(strict_types=1);

namespace Chimera\Mapping\Routing;

use Chimera\Mapping\Validator;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class SimpleEndpoint extends Endpoint
{
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    protected function validateAdditionalData(Validator $validator): void
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultMethods(): array
    {
        return ['GET'];
    }
}
