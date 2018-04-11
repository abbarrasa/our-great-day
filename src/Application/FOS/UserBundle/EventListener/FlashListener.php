<?php

namespace Application\FOS\UserBundle\EventListener;

use AppBundle\Templating\Helper\FlashMessageHelper;
use FOS\UserBundle\EventListener\FlashListener as BaseListener;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Session\Session;

//use FOS\UserBundle\FOSUserEvents;
//use Symfony\Component\EventDispatcher\Event;
//use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\Translation\TranslatorInterface;

class FlashListener extends BaseListener
{
    /** @var string[]  */
    private static $successMessages = [
        FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'change_password.flash.success',
        FOSUserEvents::PROFILE_EDIT_COMPLETED => 'profile.flash.updated',
        FOSUserEvents::REGISTRATION_COMPLETED => 'registration.flash.user_created',
        FOSUserEvents::RESETTING_RESET_COMPLETED => 'resetting.flash.success',
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
     * @param Event  $event
     * @param string $eventName
     */
    public function addSuccessFlash(Event $event, $eventName)
    {
        if (!isset(self::$successMessages[$eventName])) {
            throw new \InvalidArgumentException('This event does not correspond to a known flash message');
        }

        $this->session->getFlashBag()->add('success', $this->helper->getFlashMessage(
            'success', 'frontend.success', self::$successMessages[$eventName], [], 'FOSUserBundle'
        ));
    }
}