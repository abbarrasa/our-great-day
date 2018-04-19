<?php

namespace Application\FOS\UserBundle\EventListener;

use Application\FOS\UserBundle\Event\Events;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\EventListener\AuthenticationListener as BaseListener;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\UserBundle\Security\LoginManagerInterface;

class AuthenticationListener extends BaseListener
{
    private $router;

    public function __construct(LoginManagerInterface $loginManager, UrlGeneratorInterface $router, $firewallName)
    {
        parent::__construct($loginManager, $firewallName);
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::AUTOLOGIN_USER_INITIALIZE => 'onAutologinInitialize',
            FOSUserEvents::REGISTRATION_COMPLETED => 'authenticate',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'authenticate',
            FOSUserEvents::RESETTING_RESET_COMPLETED => 'authenticate',
            Events::AUTOLOGIN_USER_COMPLETED => 'authenticate'
        ];
    }

    /**
     * @param GetResponseNullableUserEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function onAutologinInitialize(GetResponseNullableUserEvent $event, $eventName, EventDispatcherInterface $eventDispatcher)
    {
        if (($user = $event->getUser()) === null) {
            $eventDispatcher->dispatch(Events::AUTOLOGIN_USER_USERNAME_INVALID, $event);

            $url = $this->router->generate('homepage');
            $event->setResponse(new RedirectResponse($url));
        } else if(!$user->isEnabled()) {
            $eventDispatcher->dispatch(Events::AUTOLOGIN_USER_ACCOUNT_LOCKED, $event);

            $url = $this->router->generate('homepage');
            $event->setResponse(new RedirectResponse($url));
        }
    }
}
