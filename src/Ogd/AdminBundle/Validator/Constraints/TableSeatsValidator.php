<?php

namespace AdminBundle\Validator\Constraints;

use AdminBundle\Entity\Table;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TableSeatsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TableSeats) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\TableSeats');
        }

        if (!$value instanceof Table) {
            throw new UnexpectedTypeException($value, Table::class);
        }

        if ($value->getNumberSeats() > $value->getSeats()->count()) {
            $this->context->buildViolation($constraint->numberSeats)
                ->atPath('numberSeats')
                ->addViolation();
        }
        
        if (
            $value->getFreeSeats() > $value->getNumberSeats() ||
            $value->getFreeSeats() !== ($value->getNumberSeats() - $value->getSeats()->count())
        ) {
            $this->context->buildViolation($constraint->freeSeats)
                ->atPath('freeSeats')
                ->addViolation();            
        }
    }
}