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
final class CommandHandler implements AnnotationInterface
{
    public ?string $handles;

    /** @param array{handles?: string, value?: string} $values */
    public function __construct(array $values)
    {
        $this->handles = $values['handles'] ?? $values['value'] ?? null;
    }

    /** @throws AnnotationException */
    public function validate(string $context): void
    {
        $validator = new Validator(self::class, $context);
        $validator->requiredScalar('handles', 'string', $this->handles);
    }
}
