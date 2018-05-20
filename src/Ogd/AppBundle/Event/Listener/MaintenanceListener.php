<?php

namespace AppBundle\Event\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MaintenanceListener
{
    /** @var  ContainerInterface */
    private $container;

    /**
     * MaintenanceListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Show a maintenance page.
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $maintenance = $this->container->hasParameter('maintenance') ? $this->container->getParameter('maintenance') : false;
//        $environment  = $this->container->get('kernel')->getEnvironment();
        if ($maintenance) {
            $page = $this->container->get('twig')->render('@App/maintenance.html.twig');

            $event->setResponse(new Response($page, Response::HTTP_SERVICE_UNAVAILABLE));
            $event->stopPropagation();
        }
    }
}