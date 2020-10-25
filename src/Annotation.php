<?php
declare(strict_types=1);

namespace Chimera\Mapping;

use Doctrine\Common\Annotations\AnnotationException;

interface Annotation
{
    /** @throws AnnotationException */
    public function validate(string $context): void;
}
