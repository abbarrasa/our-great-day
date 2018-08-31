<?php

namespace AdminBundle\Validator\Constraints;

use AdminBundle\Entity\Seat;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SeatGuestValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof SeatGuest) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\SeatGuest');
        }

        if (!$value instanceof Seat) {
            throw new UnexpectedTypeException($value, Seat::class);
        }

        $name = $value->getName();

        if ($value->getGuest() === null && empty($name)) {
            $this->context->buildViolation($constraint->requiredMessage)
                ->atPath('guest')
                ->addViolation();
        }
    }
}
