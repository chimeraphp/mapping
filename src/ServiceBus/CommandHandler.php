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
final class CommandHandler implements AnnotationInterface
{
    /**
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
