<?php

namespace AppBundle\Twig;

class ModulesExtension extends \Twig_Extension
{
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
        return $environment->render('@App/partials/carousel.html.twig', [
            'id' => uniqid()
        ]);
    }
}
