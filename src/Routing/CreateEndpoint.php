<?php
declare(strict_types=1);

namespace Chimera\Mapping\Routing;

use Chimera\Mapping\Validator;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class CreateEndpoint extends Endpoint
{
    public ?string $command;
    public ?string $redirectTo;
    public bool $async;

    /** @param mixed[] $values */
    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->command    = $values['command'] ?? null;
        $this->redirectTo = $values['redirectTo'] ?? null;
        $this->async      = $values['async'] ?? false;
    }

    protected function validateAdditionalData(Validator $validator): void
    {
        $validator->requiredScalar('command', 'string', $this->command);
        $validator->requiredScalar('redirectTo', 'string', $this->redirectTo);
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultMethods(): array
    {
        return ['POST'];
    }
}
