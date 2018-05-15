<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class FullName
 *
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class FullName extends Constraint
{
    public $message = 'user.name.error';
}