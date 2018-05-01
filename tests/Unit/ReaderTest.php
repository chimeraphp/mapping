<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Mapping\Tests\Unit;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader as ReaderInterface;
use Lcobucci\Chimera\Mapping\Annotation;
use Lcobucci\Chimera\Mapping\Reader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @coversDefaultClass \Lcobucci\Chimera\Mapping\Reader
 */
final class ReaderTest extends TestCase
{
    /**
     * @var ReaderInterface|MockObject
     */
    private $decorated;

    /**
     * @before
     */
    public function configureDependencies(): void
    {
        $this->decorated = $this->createMock(ReaderInterface::class);
    }

    /**
     * @test
     *
     * @covers ::fromDefault()
     * @covers ::__construct()
     */
    public function fromDefaultShouldReturnAReaderDecoratingABasicAnnotationReader(): void
    {
        $reader = Reader::fromDefault();

        self::assertAttributeInstanceOf(AnnotationReader::class, 'decorated', $reader);
    }

    /**
     * @test
     *
     * @covers ::getClassAnnotations()
     *
     * @uses \Lcobucci\Chimera\Mapping\Reader::__construct()
     */
    public function getClassAnnotationsShouldReturnOnlyValidAndRelevantAnnotations(): void
    {
        $annotation = $this->createMock(Annotation::class);
        $class      = new ReflectionClass(__CLASS__);

        $annotation->expects(self::exactly(2))
                   ->method('validate')
                   ->with('class ' . __CLASS__);

        $this->decorated->expects(self::once())
                        ->method('getClassAnnotations')
                        ->with($class)
                        ->willReturn([$annotation, new Enum(['value' => []]), $annotation]);

        $reader = new Reader($this->decorated);

        self::assertSame([$annotation, $annotation], $reader->getClassAnnotations($class));
    }

    /**
     * @test
     *
     * @covers ::getClassAnnotations()
     *
     * @uses \Lcobucci\Chimera\Mapping\Reader::__construct()
     */
    public function getClassAnnotationsShouldThrowExceptionForInvalidAnnotations(): void
    {
        $annotation = $this->createMock(Annotation::class);
        $exception  = new AnnotationException();
        $class      = new ReflectionClass(__CLASS__);

        $annotation->expects(self::once())
                   ->method('validate')
                   ->with('class ' . __CLASS__)
                   ->willThrowException($exception);

        $this->decorated->expects(self::once())
                        ->method('getClassAnnotations')
                        ->with($class)
                        ->willReturn([$annotation]);

        $reader = new Reader($this->decorated);

        $this->expectExceptionObject($exception);
        $reader->getClassAnnotations($class);
    }

    /**
     * @test
     *
     * @covers ::getClassAnnotation()
     *
     * @uses \Lcobucci\Chimera\Mapping\Reader::__construct()
     */
    public function getClassAnnotationShouldReturnNullWhenAnnotationWasNotFound(): void
    {
        $class = new ReflectionClass(__CLASS__);

        $this->decorated->expects(self::once())
                        ->method('getClassAnnotation')
                        ->with($class, 'testing')
                        ->willReturn(null);

        $reader = new Reader($this->decorated);

        self::assertNull($reader->getClassAnnotation($class, 'testing'));
    }

    /**
     * @test
     *
     * @covers ::getClassAnnotation()
     *
     * @uses \Lcobucci\Chimera\Mapping\Reader::__construct()
     */
    public function getClassAnnotationShouldThrowExceptionWhenAnnotationDataIsInvalid(): void
    {
        $annotation = $this->createMock(Annotation::class);
        $exception  = new AnnotationException();
        $class      = new ReflectionClass(__CLASS__);

        $annotation->expects(self::once())
                   ->method('validate')
                   ->with('class ' . __CLASS__)
                   ->willThrowException($exception);

        $this->decorated->expects(self::once())
                        ->method('getClassAnnotation')
                        ->with($class, 'testing')
                        ->willReturn($annotation);

        $reader = new Reader($this->decorated);

        $this->expectExceptionObject($exception);
        $reader->getClassAnnotation($class, 'testing');
    }

    /**
     * @test
     *
     * @covers ::getClassAnnotation()
     *
     * @uses \Lcobucci\Chimera\Mapping\Reader::__construct()
     */
    public function getClassAnnotationShouldReturnMatchedAnnotation(): void
    {
        $annotation = $this->createMock(Annotation::class);
        $class      = new ReflectionClass(__CLASS__);

        $annotation->expects(self::exactly(1))
                   ->method('validate')
                   ->with('class ' . __CLASS__);

        $this->decorated->expects(self::once())
                        ->method('getClassAnnotation')
                        ->with($class, 'testing')
                        ->willReturn($annotation);

        $reader = new Reader($this->decorated);

        self::assertSame($annotation, $reader->getClassAnnotation($class, 'testing'));
    }
}
