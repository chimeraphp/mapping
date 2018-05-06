<?php
declare(strict_types=1);

namespace Chimera\Mapping;

use Doctrine\Common\Annotations\AnnotationException;
use function array_intersect;
use function gettype;
use function implode;

/**
 * @internal
 */
final class Validator
{
    /**
     * @var string
     */
    private $annotation;

    /**
     * @var string
     */
    private $context;

    public function __construct(string $annotation, string $context)
    {
        $this->annotation = $annotation;
        $this->context    = $context;
    }

    /**
     * @param mixed $value
     *
     * @throws AnnotationException
     */
    public function requiredScalar(string $attribute, string $type, $value): void
    {
        if ($value === null) {
            throw AnnotationException::requiredError($attribute, $this->annotation, $this->context, $type);
        }

        $this->verifyType($attribute, $type, $value);
    }

    /**
     * @param mixed $value
     *
     * @throws AnnotationException
     */
    public function nonRequiredScalar(string $attribute, string $type, $value): void
    {
        if ($value === null) {
            return;
        }

        $this->verifyType($attribute, $type, $value);
    }

    /**
     * @param mixed[] $allowedValues
     * @param mixed   $value
     *
     * @throws AnnotationException
     */
    public function enumArray(string $attribute, array $allowedValues, $value): void
    {
        if ($value === null || $value === []) {
            throw AnnotationException::requiredError($attribute, $this->annotation, $this->context, 'array');
        }

        $this->verifyType($attribute, 'array', $value);

        if (array_intersect($value, $allowedValues) === []) {
            throw AnnotationException::enumeratorError(
                $attribute,
                $this->annotation,
                $this->context,
                $allowedValues,
                implode(', ', $value)
            );
        }
    }

    /**
     * @param mixed $value
     *
     * @throws AnnotationException
     */
    private function verifyType(string $attribute, string $type, $value): void
    {
        $actualType = gettype($value);

        if ($actualType !== $type) {
            throw AnnotationException::attributeTypeError($attribute, $this->annotation, $this->context, $type, $value);
        }
    }
}
