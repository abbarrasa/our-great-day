<?php

namespace AdminBundle\Validator\Constraints;

use AdminBundle\Entity\Table;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TableValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Table) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Table');
        }

        if (!$value instanceof Table) {
            throw new UnexpectedTypeException($value, Table::class);
        }

        if ($value->getNumberSeats() > $value->getSeats()->count()) {
            $this->context->buildViolation($constraint->maxSeats)
                ->atPath('numberSeats')
                ->addViolation();
        }
        
        if (
            $value->getFreeSeats() > $value->getNumberSeats() ||
            $value->getFreeSeats() !== ($value->getNumberSeats() - $value->getSeats()->count())
        ) {
            $this->context->buildViolation($constraint->invalidFreeSeats)
                ->atPath('freeSeats')
                ->addViolation();            
        }
    }

}

