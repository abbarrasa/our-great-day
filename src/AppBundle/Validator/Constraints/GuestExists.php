<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class GuestExists extends Constraint
{
    public $notFoundMessage        = 'not_found';
    public $multipleMatchesMessage = 'multiple_matches';
}
