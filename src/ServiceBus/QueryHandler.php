<?php
declare(strict_types=1);

namespace Chimera\Mapping\ServiceBus;

use Chimera\Mapping\Annotation as AnnotationInterface;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class QueryHandler implements AnnotationInterface
{
    /**
     * @Required
     *
     * @var string
     */
    public $handles;

    /**
     * @param mixed[] $values
     */
    public function __construct(array $values)
    {
        $this->handles = $values['handles'] ?? $values['value'] ?? null;
    }

    /**
     * @throws AnnotationException
     */
    public function validate(string $context): void
    {
        $validator = new Validator(__CLASS__, $context);
        $validator->requiredScalar('handles', 'string', $this->handles);
    }
}
