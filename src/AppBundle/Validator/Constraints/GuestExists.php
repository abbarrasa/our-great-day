<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class GuestExists extends Constraint
{
    public $incorrectNameMessage = 'Did you mean "{{ string }}"?';
    public $notFoundMessage      = 'No guests were found';
}
