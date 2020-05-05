<?php
declare(strict_types=1);

namespace Chimera\Mapping\Routing;

use Chimera\Mapping\Annotation as AnnotationInterface;
use Chimera\Mapping\Validator;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Middleware implements AnnotationInterface
{
    public string $path;
    public int $priority;
    public ?string $app;

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
        $validator = new Validator(self::class, $context);
        $validator->nonRequiredScalar('app', 'string', $this->app);
    }
}
