<?php
declare(strict_types=1);

namespace Chimera\Mapping;

use Chimera\Mapping\ServiceBus\Handler;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader as ReaderInterface;
use ReflectionClass;
use ReflectionMethod;

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

        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($this->findMethodAnnotations($method) as $annotation) {
                $annotations[] = $annotation;
            }
        }

        return $annotations;
    }

    /**
     * @return iterable<Handler>
     *
     * @throws AnnotationException
     */
    private function findMethodAnnotations(ReflectionMethod $method): iterable
    {
        foreach ($this->decorated->getMethodAnnotations($method) as $annotation) {
            if (! $annotation instanceof Handler) {
                continue;
            }

            $annotation->configure($method);

            yield $annotation;
        }
    }
}
