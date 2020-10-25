<?php
declare(strict_types=1);

namespace Chimera\Mapping\Routing;

use Chimera\Mapping\Validator;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class CreateAndFetchEndpoint extends Endpoint
{
    public ?string $command;
    public ?string $query;
    public ?string $redirectTo;

    /** @param mixed[] $values */
    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->command    = $values['command'] ?? null;
        $this->query      = $values['query'] ?? null;
        $this->redirectTo = $values['redirectTo'] ?? null;
    }

    protected function validateAdditionalData(Validator $validator): void
    {
        $validator->requiredScalar('command', 'string', $this->command);
        $validator->requiredScalar('query', 'string', $this->query);
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
