<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;


class LongitudeValidator extends ConstraintValidator
{
    const REGEX = '/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/';

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Longitude) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Longitude');
        }


        if (!preg_match(self::REGEX, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}