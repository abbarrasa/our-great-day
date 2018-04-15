<?php

namespace Application\FOS\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SecurityController extends BaseController
{
    private $eventDispatcher;
    private $tokenManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, CsrfTokenManagerInterface $tokenManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param Request $request
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function autologinAction(Request $request, $token)
    {
        $userManager = $this->userManager;
        $user = $userManager->findUserByConfirmationToken($token);
        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $userManager->updateUser($user);

        $response = $this->redirectToRoute('homepage');
        $this->eventDispatcher->dispatch(
            Events::USER_AUTOLOGIN,
            new FilterUserResponseEvent($user, $request, $response)
        );

        return $response;
    }
}