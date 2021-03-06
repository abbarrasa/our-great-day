<?php

namespace AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;

/**
 * GuestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GuestRepository extends EntityRepository
{
    public function findByCriteria(array $criteria)
    {
        $qb = $this->createQueryBuilder('g');
        foreach($criteria as $fieldname => $value) {
            if (!in_array($fieldname, ['firstname', 'lastname', 'email'])) {
                throw new ORMInvalidArgumentException(
                    sprintf("Invalid fieldname '%s' in criteria", $fieldname)
                );
            }

            if (!empty($value)) {
                if ($fieldname === 'firstname') {
                    $qb->andWhere($qb->expr()->like('g.firstname', $qb->expr()->literal('%'.$value.'%')));
                } else if ($fieldname === 'lastname') {
                    $qb->andWhere($qb->expr()->like('g.lastname', $qb->expr()->literal('%'.$value.'%')));
                } else {
                    $qb
                        ->andWhere("g.email = :email")
                        ->setParameter('email', $value)
                    ;
                }
            }
        }

        return $qb->getQuery()->getResult();
    }
}
