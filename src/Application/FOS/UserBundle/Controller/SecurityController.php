<?php

namespace Application\FOS\UserBundle\Controller;

use Application\FOS\UserBundle\Event\Events;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;

class SecurityController extends BaseController
{
    private $eventDispatcher;
    private $userManager;
    private $tokenGenerator;

    public function __construct(EventDispatcherInterface $eventDispatcher, UserManagerInterface $userManager, TokenGeneratorInterface $tokenGenerator)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param Request $request
     * @param $username
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function autologinAction(Request $request, $token)
    {
        $username = $this->tokenGenerator->decrypt($token);
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
