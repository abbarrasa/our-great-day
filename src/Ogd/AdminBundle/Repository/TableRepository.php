<?php

namespace AdminBundle\Repository;

/**
 * ReplyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TableRepository extends \Doctrine\ORM\EntityRepository
{
    public function getQueryBySeatName($name = null)
    {
        $qb = $this->createQueryBuilder('t');
        if (!empty($name)) {
            $qb
                ->leftJoin('t.seats', 's')
                ->where($qb->expr()->like('s.name', $qb->expr()->literal("%{$name}%")))
            ;
        }
                
        return $qb;
    }
}
