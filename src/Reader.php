<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader as ReaderInterface;
use ReflectionClass;
use function assert;

final class Reader
{
    /**
     * @var ReaderInterface
     */
    private $decorated;

    public function __construct(ReaderInterface $reader)
    {
        $this->decorated = $reader;
    }

    /**
     * @throws AnnotationException
     */
    public static function fromDefault(): self
    {
        return new self(new AnnotationReader());
    }

    /**
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

    /**
     * @throws AnnotationException
     */
    public function getClassAnnotation(ReflectionClass $class, string $annotationName): ?Annotation
    {
        $annotation = $this->decorated->getClassAnnotation($class, $annotationName);
        assert($annotation instanceof Annotation || $annotation === null);

        if ($annotation instanceof Annotation) {
            $annotation->validate('class ' . $class->getName());
        }

        return $annotation;
    }
}
