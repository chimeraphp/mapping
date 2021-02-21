<?php
declare(strict_types=1);

namespace Chimera\Mapping\ServiceBus;

use Chimera\Mapping\Annotation as AnnotationInterface;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;
use ReflectionMethod;
use ReflectionNamedType;

use function array_shift;

abstract class Handler implements AnnotationInterface
{
    private const PARAMETER_TYPE_MESSAGE = 'The first parameter of the handler method must be a custom class';

    public ?string $handles;
    public string $method;

    /** @param array{handles?: string, method?: string, value?: string} $values */
    final public function __construct(array $values)
    {
        $this->handles = $values['handles'] ?? $values['value'] ?? null;
        $this->method  = $values['method'] ?? 'handle';
    }

    /** @throws AnnotationException */
    final public function validate(string $context): void
    {
        $validator = new Validator(static::class, $context);
        $validator->requiredScalar('handles', 'string', $this->handles);
        $validator->requiredScalar('method', 'string', $this->method);
    }

    /** @throws AnnotationException */
    final public function configure(ReflectionMethod $method): void
    {
        $parameters = $method->getParameters();
        $parameter  = array_shift($parameters);

        if (
            $parameter === null
            || ! $parameter->getType() instanceof ReflectionNamedType
            || $parameter->getType()->isBuiltin()
        ) {
            throw AnnotationException::semanticalError(self::PARAMETER_TYPE_MESSAGE);
        }

        $this->method  = $method->getName();
        $this->handles = $parameter->getType()->getName();
    }
}
