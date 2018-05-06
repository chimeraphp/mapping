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
    /**
     * @var string
     */
    public $command;

    /**
     * @var bool
     */
    public $async;

    /**
     * @param mixed[] $values
     */
    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->command = $values['command'] ?? null;
        $this->async   = $values['async'] ?? false;
    }

    /**
     * {@inheritdoc}
     */
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
