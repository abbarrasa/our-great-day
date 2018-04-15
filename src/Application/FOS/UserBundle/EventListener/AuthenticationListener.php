<?php

namespace Application\FOS\UserBundle\EventListener;

use Application\FOS\UserBundle\Event\Events;
use FOS\UserBundle\EventListener\AuthenticationListener as BaseListener;
use FOS\UserBundle\FOSUserEvents;

class AuthenticationListener extends BaseListener
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_COMPLETED => 'authenticate',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'authenticate',
            FOSUserEvents::RESETTING_RESET_COMPLETED => 'authenticate',
            Events::USER_AUTOLOGIN => 'authenticate'
        ];
    }
}