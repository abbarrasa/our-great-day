<?php

namespace AppBundle\Validator\Constraints;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class FullNameValidator
 * @package AppBundle\Validator\Constraints
 */
class FullNameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof FullName) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\FullName');
        }

        if (!$value instanceof User) {
            throw new UnexpectedTypeException($value, User::class);
        }

        $firstname = $value->getFirstname();
        $lastname  = $value->getLastname();
        if (!empty($firstname) && empty($lastname)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('lastname')
                ->addViolation();
        }

        if (!empty($lastname) && empty($firstname)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('firstname')
                ->addViolation();
        }
    }

}