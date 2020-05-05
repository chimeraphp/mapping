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

    /**
     * @before
     */
    public function configureReader(): void
    {
        $this->reader = Reader::fromDefault();
    }

    /**
     * @param class-string<T> $className
     *
     * @throws AnnotationException
     * @throws ReflectionException
     *
     * @template T
     */
    protected function readAnnotation(string $className, string $annotation): ?Annotation
    {
        return $this->reader->getClassAnnotation(
            new ReflectionClass($className),
            $annotation
        );
    }
}
