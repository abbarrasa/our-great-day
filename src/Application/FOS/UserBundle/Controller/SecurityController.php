<?php

namespace Application\FOS\UserBundle\Controller;

use Application\FOS\UserBundle\Event\Events;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Model\UserManagerInterface;
use Nzo\UrlEncryptorBundle\UrlEncryptor\UrlEncryptor;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SecurityController extends BaseController
{
    private $eventDispatcher;
    private $userManager;
    private $encryptor;

    /**
     * SecurityController constructor.
     * @param CsrfTokenManagerInterface $tokenManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserManagerInterface $userManager
     * @param UrlEncryptor $encryptor
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager, EventDispatcherInterface $eventDispatcher, UserManagerInterface $userManager, UrlEncryptor $encryptor)
    {
        parent::__construct($tokenManager);
        $this->eventDispatcher = $eventDispatcher;
        $this->userManager = $userManager;
        $this->encryptor = $encryptor;
    }

    /**
     * @param Request $request
     * @param $token
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function autologinAction(Request $request, $token)
    {
        $username = $this->encryptor->decrypt(base64_decode(strtr($token, '-_', '+/')));
        $user = $this->userManager->findUserByUsername($username);
        $event = new GetResponseNullableUserEvent($user, $request);
        $this->eventDispatcher->dispatch(Events::AUTOLOGIN_USER_INITIALIZE, $event);

        if (($response = $event->getResponse()) !== null) {
            return $response;
        }

        $this->userManager->updateUser($user);

        $response = $this->redirectToRoute('homepage');
        $this->eventDispatcher->dispatch(
            Events::AUTOLOGIN_USER_COMPLETED,
            new FilterUserResponseEvent($user, $request, $response)
        );

        return $response;
    }
}
