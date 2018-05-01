<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Functional;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Lcobucci\Chimera\Mapping\Annotation;
use Lcobucci\Chimera\Mapping\Reader;
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
     * @beforeClass
     */
    public static function registerAutoLoader(): void
    {
        AnnotationRegistry::registerUniqueLoader('class_exists');
    }

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
