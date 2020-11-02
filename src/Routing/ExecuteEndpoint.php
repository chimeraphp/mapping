<?php
declare(strict_types=1);

namespace Chimera\Mapping\Routing;

use Chimera\Mapping\Validator;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class ExecuteEndpoint extends Endpoint
{
    public ?string $command;
    public bool $async;

    /** @param array{path?: string, value?: string, name?: string, app?: string, methods?: list<string>, command?: string, async?: bool} $values */
    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->command = $values['command'] ?? null;
        $this->async   = $values['async'] ?? false;
    }

    protected function validateAdditionalData(Validator $validator): void
    {
        $validator->requiredScalar('command', 'string', $this->command);
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultMethods(): array
    {
        return ['PUT', 'PATCH', 'DELETE'];
    }
}
