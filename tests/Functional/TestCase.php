<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Functional;

use Chimera\Mapping\Annotation;
use Chimera\Mapping\Reader;
use Doctrine\Common\Annotations\AnnotationException;
use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;

abstract class TestCase extends BaseTestCase
{
    private Reader $reader;

    /** @before */
    public function configureReader(): void
    {
        $this->reader = Reader::fromDefault();
    }

    /**
     * @template T of Annotation
     *
     * @param class-string    $className
     * @param class-string<T> $annotationName
     *
     * @return T|null
     *
     * @throws AnnotationException
     * @throws ReflectionException
     */
    protected function readAnnotation(string $className, string $annotationName): ?Annotation
    {
        foreach ($this->reader->getClassAnnotations(new ReflectionClass($className)) as $annotation) {
            if ($annotation instanceof $annotationName) {
                return $annotation;
            }
        }

        return null;
    }
}
