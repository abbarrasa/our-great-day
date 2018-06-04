<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\Greeting;
use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    public function getQueryAllPublished()
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.published = :published')
            ->setParameter('published', true)
            ->orderBy('p.publishedAt', 'DESC')
        ;
    }
    
    public function getQueryAllCommentsPost($post)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('c')
            ->innerJoin('p.comments', 'c')
            ->where('p.id = :post')
            ->setParameter('post', $post)
            ->orderBy('p.createdAt', 'DESC')
        ;
    }    
}
