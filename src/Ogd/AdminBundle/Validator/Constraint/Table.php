<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Table
 *
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class Table extends Constraint
{
    public $message = 'table.number_seats.error';
}