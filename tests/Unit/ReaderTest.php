<?php
declare(strict_types=1);

namespace Chimera\Mapping\Tests\Unit;

use Chimera\Mapping\Annotation;
use Chimera\Mapping\Reader;
use Chimera\Mapping\ServiceBus\Handler;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader as ReaderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

use function get_class;

/** @coversDefaultClass \Chimera\Mapping\Reader */
final class ReaderTest extends TestCase
{
    /** @var ReaderInterface|MockObject */
    private $decorated;

    /** @before */
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
        self::assertEquals(new Reader(new AnnotationReader()), Reader::fromDefault());
    }

    /**
     * @test
     *
     * @covers ::getClassAnnotations()
     * @covers ::findMethodAnnotations()
     *
     * @uses \Chimera\Mapping\Reader::__construct()
     */
    public function getClassAnnotationsShouldReturnOnlyValidAndRelevantAnnotations(): void
    {
        $annotation = $this->createMock(Annotation::class);
        $class      = new ReflectionClass(self::class);

        $annotation->expects(self::exactly(2))
                   ->method('validate')
                   ->with('class ' . self::class);

        $this->decorated->expects(self::once())
                        ->method('getClassAnnotations')
                        ->with($class)
                        ->willReturn([$annotation, new Enum(['value' => []]), $annotation]);

        $this->decorated->method('getMethodAnnotations')->willReturn([]);

        $reader = new Reader($this->decorated);

        self::assertSame([$annotation, $annotation], $reader->getClassAnnotations($class));
    }

    /**
     * @test
     *
     * @covers ::getClassAnnotations()
     * @covers ::findMethodAnnotations()
     *
     * @uses \Chimera\Mapping\Reader::__construct()
     * @uses \Chimera\Mapping\ServiceBus\Handler
     */
    public function getClassAnnotationsShouldIncludeMethodAnnotations(): void
    {
        $handler = new class {
            public function testing(Reader $reader): void // phpcs:ignore
            {
            }
        };

        $annotation = $this->createMock(Annotation::class);
        $class      = new ReflectionClass($handler);

        $annotation->expects(self::exactly(2))
                   ->method('validate')
                   ->with('class ' . get_class($handler));

        $this->decorated->expects(self::once())
                        ->method('getClassAnnotations')
                        ->with($class)
                        ->willReturn([$annotation, new Enum(['value' => []]), $annotation]);

        $handlerAnnotation = $this->createMock(Handler::class);

        $this->decorated->method('getMethodAnnotations')
                        ->willReturn([$handlerAnnotation, new Enum(['value' => []]), $handlerAnnotation]);

        $reader = new Reader($this->decorated);

        self::assertSame(
            [$annotation, $annotation, $handlerAnnotation, $handlerAnnotation],
            $reader->getClassAnnotations($class)
        );
    }

    /**
     * @test
     *
     * @covers ::getClassAnnotations()
     *
     * @uses \Chimera\Mapping\Reader::__construct()
     */
    public function getClassAnnotationsShouldThrowExceptionForInvalidAnnotations(): void
    {
        $annotation = $this->createMock(Annotation::class);
        $exception  = new AnnotationException();
        $class      = new ReflectionClass(self::class);

        $annotation->expects(self::once())
                   ->method('validate')
                   ->with('class ' . self::class)
                   ->willThrowException($exception);

        $this->decorated->expects(self::once())
                        ->method('getClassAnnotations')
                        ->with($class)
                        ->willReturn([$annotation]);

        $reader = new Reader($this->decorated);

        $this->expectExceptionObject($exception);
        $reader->getClassAnnotations($class);
    }
}
