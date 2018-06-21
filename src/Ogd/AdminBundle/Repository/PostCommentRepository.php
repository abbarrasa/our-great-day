<?php

namespace AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

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
        return $this
            ->createQueryBuilder('c')
            ->where('c.post = :post')
            ->setParameter('post', $post)
            ->orderBy('c.createdAt', 'DESC')
        ;
    }
}