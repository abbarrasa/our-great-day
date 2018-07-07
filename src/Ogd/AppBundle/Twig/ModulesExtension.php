<?php

namespace AppBundle\Twig;

use AdminBundle\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class ModulesExtension extends \Twig_Extension
{
    /** @var EntityManagerInterface */
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_modules';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('carousel', [$this, 'carouselFunction'], [
                    'needs_environment' => true, 'pre_escape' => 'html', 'is_safe' => ['html']
            ])
        ];
    }

    public function carouselFunction(\Twig_Environment $environment)
    {
        $posts = $this->em->getRepository(Post::class)->findLastPublished();
        
        return $environment->render('@App/partials/carousel.html.twig', [
            'id' => uniqid(),
            'posts' => $posts
        ]);
    }
}
