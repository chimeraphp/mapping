<?php
declare(strict_types=1);

namespace Chimera\Mapping;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader as ReaderInterface;
use ReflectionClass;

final class Reader
{
    private ReaderInterface $decorated;

    public function __construct(ReaderInterface $reader)
    {
        $this->decorated = $reader;
    }

    /** @throws AnnotationException */
    public static function fromDefault(): self
    {
        return new self(new AnnotationReader());
    }

    /**
     * @param ReflectionClass<object> $class
     *
     * @return Annotation[]
     *
     * @throws AnnotationException
     */
    public function getClassAnnotations(ReflectionClass $class): array
    {
        $annotations = [];

        foreach ($this->decorated->getClassAnnotations($class) as $annotation) {
            if (! $annotation instanceof Annotation) {
                continue;
            }

            $annotation->validate('class ' . $class->getName());

            $annotations[] = $annotation;
        }

        return $annotations;
    }
}
