<?php

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Password) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Password');
        }

        $this->context->getValidator()
            ->inContext($this->context)
            ->validate($value, new Length(['min' => 8]));

        if (!preg_match('/^(?=.*\d)(?!.*\s).*$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}