<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class TableSeats
 *
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class TableSeats extends Constraint
{
    public $message = 'table.number_seats.error';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
