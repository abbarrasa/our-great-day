<?php

namespace Application\FOS\UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseController;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResettingController extends BaseController
{
    /**
     * @var int
     */
    private $retryTtl;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param FactoryInterface         $formFactory
     * @param UserManagerInterface     $userManager
     * @param TokenGeneratorInterface  $tokenGenerator
     * @param MailerInterface          $mailer
     * @param int                      $retryTtl
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FactoryInterface $formFactory,
        UserManagerInterface $userManager,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mailer,
        $retryTtl
    ) {
        parent::__construct($eventDispatcher, $formFactory, $userManager, $tokenGenerator, $mailer, $retryTtl);
        $this->retryTtl = $retryTtl;
    }

    /**
     * Tell the user to check his email provider.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function checkEmailAction(Request $request)
    {
        $username = $request->get('username');

        if (empty($username)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->generateUrl('fos_user_resetting_request'));
        }

        return $this->render('@FOSUser/Resetting/check_email.html.twig', array(
            'tokenLifetime' => ceil($this->retryTtl / 3600),
        ));
    }
}