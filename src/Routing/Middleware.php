<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Routing;

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
     * @var string
     */
    public $path;

    /**
     * @var int
     */
    public $priority;

    /**
     * @var string|null
     */
    public $app;

    /**
     * @param mixed[] $values
     */
    public function __construct(array $values)
    {
        $this->path     = $values['path'] ?? $values['value'] ?? '/';
        $this->app      = $values['app'] ?? null;
        $this->priority = $values['priority'] ?? 0;
    }

    /**
     * @throws AnnotationException
     */
    public function validate(string $context): void
    {
        $validator = new Validator(__CLASS__, $context);
        $validator->requiredScalar('path', 'string', $this->path);
        $validator->nonRequiredScalar('app', 'string', $this->app);
        $validator->nonRequiredScalar('priority', 'integer', $this->priority);
    }
}
