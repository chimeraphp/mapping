<?php
declare(strict_types=1);

namespace Chimera\Mapping;

use Doctrine\Common\Annotations\AnnotationException;

use function array_intersect;
use function implode;
use function trim;

/** @internal */
final class Validator
{
    private string $annotation;
    private string $context;

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

        $this->requireNonEmptyString($attribute, $type, $value);
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

        $this->requireNonEmptyString($attribute, $type, $value);
    }

    /**
     * @param mixed $value
     *
     * @throws AnnotationException
     */
    private function requireNonEmptyString(string $attribute, string $type, $value): void
    {
        if ($type !== 'string' || trim($value) !== '') {
            return;
        }

        throw AnnotationException::requiredError($attribute, $this->annotation, $this->context, $type);
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

        if (array_intersect($value, $allowedValues) === []) {
            // @phpstan-ignore-next-line
            throw AnnotationException::enumeratorError(
                $attribute,
                $this->annotation,
                $this->context,
                $allowedValues,
                implode(', ', $value)
            );
        }
    }
}
