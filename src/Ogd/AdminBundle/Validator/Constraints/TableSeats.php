<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Table
 *
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class TableSeats extends Constraint
{
    public $numberSeats = 'table.number_seats.error';
    public $freeSeats   = 'table.free_seats.error';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
