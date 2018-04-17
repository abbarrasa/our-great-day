<?php

namespace Application\FOS\UserBundle\EventListener;

use AppBundle\Templating\Helper\FlashMessageHelper;
use Application\FOS\UserBundle\Event\Events;
use FOS\UserBundle\EventListener\FlashListener as BaseListener;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Session\Session;


class FlashListener extends BaseListener
{
    /** @var string[]  */
    private static $successMessages = [
        FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'change_password.flash.success',
        FOSUserEvents::PROFILE_EDIT_COMPLETED => 'profile.flash.updated',
        FOSUserEvents::REGISTRATION_COMPLETED => 'registration.flash.user_created',
        FOSUserEvents::RESETTING_RESET_COMPLETED => 'resetting.flash.success',
    ];

    /** @var string[]  */
    private static $errorMessages = [
        Events::RESETTING_REQUEST_USERNAME_INVALID => 'resetting.flash.error.username',
        Events::RESETTING_ACCOUNT_LOCKED => 'resetting.flash.error.account_locked',    
        Events::AUTOLOGIN_USER_ACCOUNT_LOCKED = 'autologin.flash.error.account.locked';        
    ];

    /** @var Session */
    private $session;

    /** @var FlashMessageHelper */
    private $helper;

    /**
     * FlashListener constructor.
     *
     * @param Session $session
     * @param FlashMessageHelper $helper
     */
    public function __construct(Session $session, FlashMessageHelper $helper)
    {
        $this->session = $session;
        $this->helper  = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'addSuccessFlash',
            FOSUserEvents::PROFILE_EDIT_COMPLETED => 'addSuccessFlash',
            FOSUserEvents::REGISTRATION_COMPLETED => 'addSuccessFlash',
            FOSUserEvents::RESETTING_RESET_COMPLETED => 'addSuccessFlash',
            Events::RESETTING_REQUEST_USERNAME_INVALID => 'addErrorFlash',
            Events::RESETTING_ACCOUNT_LOCKED => 'addErrorFlash',
            Events::AUTOLOGIN_USER_ACCOUNT_LOCKED => 'addErrorFlash'
        );
    }


    /**
     * @param Event  $event
     * @param string $eventName
     */
    public function addSuccessFlash(Event $event, $eventName)
    {
        if (!isset(self::$successMessages[$eventName])) {
            throw new \InvalidArgumentException('This event does not correspond to a known flash message');
        }

        $this->session->getFlashBag()->add('success', $this->helper->getFlashMessage(
            'success', 'flash.success', self::$successMessages[$eventName], [], 'FOSUserBundle'
        ));
    }

    /**
     * @param Event  $event
     * @param string $eventName
     */
    public function addErrorFlash(Event $event, $eventName)
    {
        if (!isset(self::$errorMessages[$eventName])) {
            throw new \InvalidArgumentException('This event does not correspond to a known flash message');
        }

        $this->session->getFlashBag()->add('error', $this->helper->getFlashMessage(
            'error', 'flash.error', self::$errorMessages[$eventName], [], 'FOSUserBundle'
        ));
    }

}
