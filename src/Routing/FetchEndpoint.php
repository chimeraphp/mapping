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
    /**
     * @var string
     */
    public $query;

    /**
     * @param mixed[] $values
     */
    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->query = $values['query'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
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
