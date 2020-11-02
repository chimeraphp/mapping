<?php
declare(strict_types=1);

namespace Chimera\Mapping\Routing;

use Chimera\Mapping\Validator;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class ExecuteAndFetchEndpoint extends Endpoint
{
    public ?string $command;
    public ?string $query;

    /** @param array{path?: string, value?: string, name?: string, app?: string, methods?: list<string>, command?: string, query?: string} $values */
    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->command = $values['command'] ?? null;
        $this->query   = $values['query'] ?? null;
    }

    protected function validateAdditionalData(Validator $validator): void
    {
        $validator->requiredScalar('command', 'string', $this->command);
        $validator->requiredScalar('query', 'string', $this->query);
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultMethods(): array
    {
        return ['PUT', 'PATCH'];
    }
}
