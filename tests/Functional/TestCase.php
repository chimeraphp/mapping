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
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @before
     */
    public function configureReader(): void
    {
        $this->reader = Reader::fromDefault();
    }

    /**
     * @throws AnnotationException
     * @throws ReflectionException
     */
    protected function readAnnotation(string $className, string $annotation): ?Annotation
    {
        return $this->reader->getClassAnnotation(
            new ReflectionClass($className),
            $annotation
        );
    }
}
