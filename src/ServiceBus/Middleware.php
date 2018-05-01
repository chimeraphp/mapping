<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\ServiceBus;

use Doctrine\Common\Annotations\AnnotationException;
use Lcobucci\Chimera\Mapping\Annotation as AnnotationInterface;
use Lcobucci\Chimera\Mapping\Validator;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Middleware implements AnnotationInterface
{
    /**
     * @var string|null
     */
    public $bus;

    /**
     * @var int
     */
    public $priority;

    /**
     * @param mixed[] $values
     */
    public function __construct(array $values)
    {
        $this->bus      = $values['bus'] ?? $values['value'] ?? null;
        $this->priority = $values['priority'] ?? 0;
    }

    /**
     * @throws AnnotationException
     */
    public function validate(string $context): void
    {
        $validator = new Validator(__CLASS__, $context);
        $validator->nonRequiredScalar('bus', 'string', $this->bus);
        $validator->requiredScalar('priority', 'integer', $this->priority);
    }
}
