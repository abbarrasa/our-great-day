<?php

namespace Application\FOS\UserBundle\EventListener;

use Application\FOS\UserBundle\Event\Events;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResettingListener implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var int
     */
    private $tokenTtl;

    /**
     * ResettingListener constructor.
     *
     * @param UrlGeneratorInterface $router
     * @param int                   $tokenTtl
     */
    public function __construct(UrlGeneratorInterface $router, $tokenTtl)
    {
        $this->router = $router;
        $this->tokenTtl = $tokenTtl;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE => 'onResettingSendEmailInitialize',
            FOSUserEvents::RESETTING_RESET_INITIALIZE => 'onResettingResetInitialize',
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResettingResetSuccess',
            FOSUserEvents::RESETTING_RESET_REQUEST => 'onResettingResetRequest',
        );
    }

    /**
     * @param GetResponseNullableUserEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function onResettingSendEmailInitialize(GetResponseNullableUserEvent $event, $eventName, EventDispatcherInterface $eventDispatcher)
    {
        if (null === $event->getUser()) {
            $eventDispatcher->dispatch(Events::RESETTING_REQUEST_USERNAME_INVALID, $event);

            $url = $this->router->generate('fos_user_resetting_request');
            $event->setResponse(new RedirectResponse($url));
        }
    }

    /**
     * @param GetResponseUserEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function onResettingResetRequest(GetResponseUserEvent $event, $eventName, EventDispatcherInterface $eventDispatcher)
    {
        if (!$event->getUser()->isEnabled()) {
            $eventDispatcher->dispatch(Events::RESETTING_REQUEST_ACCOUNT_LOCKED, $event);

            $url = $this->router->generate('fos_user_resetting_request');
            $event->setResponse(new RedirectResponse($url));
        }
    }

    /**
     * @param GetResponseUserEvent $event
     */
    public function onResettingResetInitialize(GetResponseUserEvent $event)
    {
        if (!$event->getUser()->isPasswordRequestNonExpired($this->tokenTtl)) {
            $event->setResponse(new RedirectResponse($this->router->generate('fos_user_resetting_request')));
        }
    }

    /**
     * @param FormEvent $event
     */
    public function onResettingResetSuccess(FormEvent $event)
    {
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();
        $user->setConfirmationToken(null);
        $user->setPasswordRequestedAt(null);
        $user->setEnabled(true);

        $url = $this->router->generate('homepage');
        $event->setResponse(new RedirectResponse($url));
    }
}