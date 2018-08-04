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

        $firstname = $value->getFirstname();
        $lastname  = $value->getLastname();

        if ($value->getGuest() === null && empty($firstname) && empty($lastname)) {
            $this->context->buildViolation($constraint->requiredMessage)
                ->atPath('guest')
                ->addViolation();
        }

        if (!empty($firstname) && empty($lastname)) {
            $this->context->buildViolation($constraint->lastnameMessage)
                ->atPath('lastname')
                ->addViolation();
        }

        if (empty($firstname) && !empty($lastname)) {
            $this->context->buildViolation($constraint->firstnameMessage)
                ->atPath('firstname')
                ->addViolation();
        }
    }
}