<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;


class LatitudeValidator extends ConstraintValidator
{
    const REGEX = '/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/';

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Latitude) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Latitude');
        }


        if (!preg_match(self::REGEX, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}