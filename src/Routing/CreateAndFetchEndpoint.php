<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Routing;

use Lcobucci\Chimera\Mapping\Validator;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class CreateAndFetchEndpoint extends Endpoint
{
    /**
     * @var string
     */
    public $command;

    /**
     * @var string
     */
    public $query;

    /**
     * @var string
     */
    public $redirectTo;

    /**
     * @param mixed[] $values
     */
    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->command    = $values['command'] ?? null;
        $this->query      = $values['query'] ?? null;
        $this->redirectTo = $values['redirectTo'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
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
