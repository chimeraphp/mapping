<?php
declare(strict_types=1);

namespace Chimera\Mapping\Routing;

use Chimera\Mapping\Validator;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class FetchEndpoint extends Endpoint
{
    public ?string $query;

    /** @param array{path?: string, value?: string, name?: string, app?: string, methods?: list<string>, query?: string} $values */
    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->query = $values['query'] ?? null;
    }

    protected function validateAdditionalData(Validator $validator): void
    {
        $validator->requiredScalar('query', 'string', $this->query);
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultMethods(): array
    {
        return ['GET'];
    }
}
