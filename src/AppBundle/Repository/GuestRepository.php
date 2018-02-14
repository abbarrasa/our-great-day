<?php

namespace AppBundle\Repository;

/**
 * GuestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GuestRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByCriteria(array $criteria)
    {
        $queryBuilder = $this->createQueryBuilder('g')
        foreach($criteria as $field => $value) {
            if (!in_array($field, array('firstname', 'lastname', 'email')) {
                throw new \Exception();
            }
                
            $queryBuilder->andWhere($queryBuilder->expr()->like($field, $value))           
        }
        
        return $queryBuilder->getQuery()->getResult();
    }
}
