<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Guest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class UserEditValidator
 *
 */
class GuestExistsValidator extends ConstraintValidator
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * GuestExistsValidator constructor.
     * @param EntityManager EntityManagerInterface
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Realiza la validacion a medida
     *
     * @param mixed $value El valor a validar
     * @param Constraint $constraint El constraint para la validación
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof GuestExists) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\GuestExists');
        }
        
        $guests = $this->em->getRepository(Guest::class)->findByCriteria($value);
        if (count($guests) > 1) {
            $this
                ->context
                ->buildViolation($constraint->multipleMatchesMessage)
                ->addViolation()
            ;
        } else if (count($guests) == 0) {
            $this
                ->context
                ->buildViolation($constraint->notFoundMessage)
                ->addViolation()
            ;
        }
    }
}
