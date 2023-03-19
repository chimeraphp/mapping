<?php
declare(strict_types=1);

namespace Chimera\Mapping;

use Doctrine\Common\Annotations\AnnotationException;

use function array_intersect;
use function assert;
use function implode;
use function is_array;
use function trim;

/** @internal */
final class Validator
{
    public function __construct(private string $annotation, private string $context)
    {
    }

    /** @throws AnnotationException */
    public function requiredScalar(string $attribute, string $type, mixed $value): void
    {
        if ($value === null) {
            throw AnnotationException::requiredError($attribute, $this->annotation, $this->context, $type);
        }

        $this->requireNonEmptyString($attribute, $type, $value);
    }

    /** @throws AnnotationException */
    public function nonRequiredScalar(string $attribute, string $type, mixed $value): void
    {
        if ($value === null) {
            return;
        }

        $this->requireNonEmptyString($attribute, $type, $value);
    }

    /** @throws AnnotationException */
    private function requireNonEmptyString(string $attribute, string $type, mixed $value): void
    {
        // @phpstan-ignore-next-line
        if ($type !== 'string' || trim($value) !== '') {
            return;
        }

        throw AnnotationException::requiredError($attribute, $this->annotation, $this->context, $type);
    }

    /**
     * @param list<string> $allowedValues
     *
     * @throws AnnotationException
     */
    public function enumArray(string $attribute, array $allowedValues, mixed $value): void
    {
        if ($value === null || $value === []) {
            throw AnnotationException::requiredError($attribute, $this->annotation, $this->context, 'array');
        }

        assert(is_array($value));

        if (array_intersect($value, $allowedValues) === []) {
            throw AnnotationException::enumeratorError(
                $attribute,
                $this->annotation,
                $this->context,
                $allowedValues,
                implode(', ', $value),
            );
        }
    }
}
