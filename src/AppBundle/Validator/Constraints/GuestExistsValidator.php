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

        $firstname  = $value['firstname'];
        $lastname   = $value['lastname'];
        $email      = isset($value['email']) ? $value['email'] : null;
        $repository = $this->em->getRepository(Guest::class);
        $guest      = $repository->findOneBy(['firstname' => $firstname, 'lastname' => $lastname]);

        if ($guest === null) {
            if ($email !== null &&
                ($guest = $repository->findOneBy(['email' => $email])) != null) {
                $this->addViolations($constraint, $guest, $firstname, $lastname);
            } else if (($guest = $repository->findOneBy(['lastname' => $lastname])) != null) {
                $this->addViolations($constraint, $guest, $firstname, $lastname);
            } else if (($guest = $repository->findOneBy(['firstname' => $firstname])) != null) {
                $this->addViolations($constraint, $guest, $firstname, $lastname);
            } else {
                $this
                    ->context
                    ->buildViolation($constraint->notFoundMessage)
                    ->addViolation();
            }
        }

    }


    private function addViolations(Constraint $constraint, Guest $guest, $firstname, $lastname)
    {
        if ($guest->getFirstname() !== $firstname) {
            $this
                ->context
                ->buildViolation($constraint->incorrectNameMessage)
                ->atPath('firstname')
                ->setParameter('{{ string }}', $guest->getFirstname())
                ->addViolation();
        }

        if ($guest->getLastname() !== $lastname) {
            $this
                ->context
                ->buildViolation($constraint->incorrectNameMessage)
                ->atPath('lastname')
                ->setParameter('{{ string }}', $guest->getLastname())
                ->addViolation();
        }
    }
}
