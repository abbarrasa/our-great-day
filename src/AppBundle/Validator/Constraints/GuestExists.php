<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class GuestExists extends Constraint
{
    public $notFoundMessage        = 'guest.not_found';
    public $multipleMatchesMessage = 'guest.multiple_matches';
}
