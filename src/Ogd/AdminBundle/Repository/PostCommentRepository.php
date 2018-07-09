<?php

namespace AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use AdminBundle\Entity\PostComment;

/**
 * PostCommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostCommentRepository extends EntityRepository
{
    public function getQueryAllByPost($post)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->addQueryDiscriminator($qb, 'c')
            ->andWhere('c.post = :post')
            ->setParameter('post', $post)
            ->orderBy('c.createdAt', 'DESC')
        ;
            
        return $qb;
    }
    
    protected function addQueryDiscriminator(QueryBuilder $qb, $alias)
    {
        $qb
            ->where($alias . ' INSTANCE OF :dtype')
            ->setParameter('dtype', PostComment::class)
        ;
        
        return $qb;
    }
}
