<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class SeatGuest
 *
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class SeatGuest extends Constraint
{
    public $requiredMessage = 'table.seat_guest.error.required';
    public $firstnameMessage = 'table.seat_guest.error.firstname';
    public $lastnameMessage = 'table.seat_guest.error.lastname';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
